import React from 'react';
import ReactDOM from 'react-dom/client';

import './bootstrap';
import App from './components/App.jsx';

const container = document.getElementById('app');

if (container) {
  ReactDOM.createRoot(container).render(
    <React.StrictMode>
      <App/>
    </React.StrictMode>
  );
}
