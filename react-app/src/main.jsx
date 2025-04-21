// main.jsx

import React from 'react'
import ReactDOM from 'react-dom/client'

import App from './App'
import AdminPanel from './components/Admin/AdminPanel'
import Wishlist from './components/Wishlist'
import Carrito from './components/Carrito'
import ProductForm from './components/Admin/ProductForm'
import Destacados from './components/Destacados'

const el =
  document.getElementById('admin-panel-root') ||
  document.getElementById('wishlist-root') ||
  document.getElementById('carrito-root') ||
  document.getElementById('product-form-root') ||
  document.getElementById('destacados-root') ||
  document.getElementById('root')

if (!el) {
  console.warn('⚠️ No se encontró un contenedor válido para montar React.')
} else {
  const root = ReactDOM.createRoot(el)

  switch (el.id) {
    case 'admin-panel-root':
      root.render(<AdminPanel />)
      break
    case 'wishlist-root':
      root.render(<Wishlist />)
      break
    case 'carrito-root':
      root.render(<Carrito />)
      break
    case 'product-form-root':
      root.render(
        <ProductForm
          producto={null}
          onClose={() => console.log('cerrar')}
          onSave={() => console.log('guardar')}
        />
      )
      break
    case 'destacados-root':
      root.render(<Destacados />)
      break
    case 'root':
      root.render(<App />)
      break
    default:
      console.warn(`⚠️ Contenedor con ID no reconocido: ${el.id}`)
  }
}
