import React from 'react';

import WeatherWidget from './WeatherWidget.jsx';

const App = () => {
  return (
    <>
      <WeatherWidget city='Sofia' source='internal' />
      <WeatherWidget city='Sofia' source='external' />
    </>
  )
}

export default App
