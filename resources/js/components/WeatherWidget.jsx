import React from 'react';
import { useState, useEffect } from 'react';
import axios from 'axios';

import LittleSpinner from './LittleSpinner.jsx';
import { capitalizeFirstLetter } from '../utils.js';

const WeatherWidget = ({ city, source = 'internal' }) => {
  const [loading, setLoading] = useState(true);
  const [data, setData] = useState(null);
  const [error, setError] = useState(null)

  useEffect(() => {
    let ignore = false;

    (async () => {
      try {
        const response = await axios.get(`/api/v1/weather/${city}?source=${source}`);

        if (ignore) return;

        setData(response.data);
      } catch (err) {
        console.error(err);
        setError(err?.response?.data?.message ?? 'Error while fetching');
      } finally {
        setLoading(false);
      }
    })();

    return () => {
      ignore = true;
    }
  }, []);

  return (
    <div className="max-w-sm mx-auto p-4 bg-blue-500 text-white rounded-lg shadow-lg mb-12 transition-opacity duration-300 hover:opacity-90">
      <div className="flex items-center justify-between">
        <div>
          <h2 className="text-2xl font-bold">{capitalizeFirstLetter(city)}</h2>
        </div>
        <div className="text-5xl font-bold">
          {loading && <LittleSpinner/>
            || data && <div className="text-6xl font-bold">{data.temperature}°C</div>
          }
        </div>
      </div>
      <div className="mt-4 flex items-center justify-between">
        <div className="flex items-center space-x-2">
          <img src="https://img.icons8.com/ios/50/ffffff/database.png" alt="Source" className="w-6 h-6"/>
          <p className="text-sm">{capitalizeFirstLetter(source)} source</p>
        </div>
        <div className="flex items-center space-x-2 bg-white text-black py-1 px-2 rounded">
          {/*<p className="text-lg font-bold">Trend</p>*/}
          {loading && <LittleSpinner/>
            || data && <p className="text-2xl">{data.trend}</p>
            || error && <p className="text-base text-red-600">{error}</p>
          }
        </div>
      </div>
    </div>
  )
}

export default WeatherWidget
