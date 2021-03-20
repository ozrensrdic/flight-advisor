<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Route;
use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CityController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $cities = City::all();

        return view('cities.index', compact('cities'));
    }

    /**
     * @return Response
     */
    public function create(): View
    {
        return view('cities.create');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:cities',
            'country' => 'required',
        ]);

        $airportData = $this->getAirportData($request->all());

        if (empty($airportData)) {
            return redirect()->route('cities.index')
                ->with('error', 'Country and city not found on the airport list.');
        }

        /** @var City $addedCity */
        $addedCity = City::create($request->all());

        $airportData['city_id'] = $addedCity->id;
        $airportData['country'] = $addedCity->country;

        $airport = Airport::create($airportData);

        $this->updateRoutes($airport);

        return redirect()->route('cities.index')
            ->with('success', 'City created successfully.');
    }

    /**
     * @param  City  $city
     * @return Response
     */
    public function show(City $city): View
    {
        $comments = $city->comments()->get()->sortByDesc('updated_at');

        return view('cities.show', compact('city', 'comments'));
    }

    /**
     * @param  City $city
     * @return Response
     */
    public function edit(City $city): View
    {
        return view('cities.edit', compact('city'));
    }

    /**
     * @param  Request  $request
     * @param  City $city
     * @return Response
     */
    public function update(Request $request, City $city): Response
    {
        $request->validate([
            'name' => 'required',
            'country' => 'required',
            'description' => 'required',
        ]);

        $city->update($request->all());

        return redirect()->route('cities.index')
            ->with('success', 'City updated successfully');
    }

    /**
     * @param  City  $city
     * @return Response
     */
    public function destroy(City $city): Response
    {
        $city->delete();

        return redirect()->route('cities.index')
            ->with('success', 'City deleted successfully');
    }

    /**
     * @param array $cityInputs
     * @return array
     */
    protected function getAirportData(array $cityInputs): array
    {
        $handle = fopen(Storage::path('local') . '/airports/airports.txt', "r");

        while ($csvLine = fgetcsv($handle)) {
            try {
                $cityName = $csvLine[2];
                $country = $csvLine[3];
                $airportData = [
                    'id' => $csvLine[0],
                    'name' => $csvLine[1],
                    'city_id' => $csvLine[2],
                    'country' => $csvLine[3],
                    'iata' => $csvLine[4],
                    'icao' => $csvLine[5],
                    'latitude' => $csvLine[6],
                    'longitude' => $csvLine[7],
                    'altitude' => $csvLine[8],
                    'dst' => $csvLine[9],
                    'timezone' => $csvLine[10],
                    'type' => $csvLine[11],
                    'source' => $csvLine[12],
                ];

            } catch (\Exception $e) {
                Log::warning('Not all rows are in right format');
                continue;
            }

            if (strtolower($cityName) === strtolower($cityInputs['name']) &&
                (strtolower($country) === strtolower($cityInputs['country']))) {
                return $airportData;
            }

        }

        return [];
    }

    /**
     * @param Airport $airport
     */
    protected function updateRoutes(Airport $airport)
    {
        $handle = fopen(Storage::path('local') . '/airports/routes.txt', "r");
        $airportsIds = Airport::pluck('id')->toArray();

        while ($csvLine = fgetcsv($handle)) {
            try {
                $sourceAirport = (int) $csvLine[3];
                $destinationAirport = (int) $csvLine[5];
                $airlineId = $csvLine[1];

                $routeData = [
                    'airline' => $csvLine[0],
                    'airline_id' => $csvLine[1],
                    'source_airport_id' => $csvLine[3],
                    'destination_airport_id' => $csvLine[5],
                    'codeshare' => $csvLine[6],
                    'stops' => $csvLine[7],
                    'equipment' => $csvLine[8],
                    'price' => $csvLine[9],
                ];


                if (($airport->id === $sourceAirport && in_array($destinationAirport, $airportsIds)) ||
                    ($airport->id === $destinationAirport && in_array($sourceAirport, $airportsIds))
                ) {

                    Route::firstOrCreate(
                        [
                            'source_airport_id' => $sourceAirport,
                            'destination_airport_id' => $destinationAirport
                        ],
                        $routeData
                    );
                }

            } catch (\Exception $e) {
                Log::warning('Not all rows are in right format');
                continue;
            }
        }
    }
}
