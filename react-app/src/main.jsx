import React from 'react'
import { createRoot } from 'react-dom/client'
import './styles.css'
import 'bootstrap/dist/css/bootstrap.min.css';
import './style.css';
import App from './registration_form'

const container = document.getElementById('root')
const root = createRoot(container)
root.render(<App />)
