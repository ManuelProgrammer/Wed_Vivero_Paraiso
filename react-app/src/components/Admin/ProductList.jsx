import React, { useEffect, useState } from 'react'
import ProductForm from './ProductForm'

export default function ProductList() {
  const [productos, setProductos] = useState([])
  const [productoEditando, setProductoEditando] = useState(null)

  useEffect(() => {
    cargarProductos()
  }, [])

  const cargarProductos = () => {
    fetch('/api/productos.php')
      .then(res => res.json())
      .then(setProductos)
      .catch(err => alert('❌ Error al cargar productos'))
  }

  const eliminarProducto = (id) => {
    if (!confirm('¿Eliminar este producto?')) return

    const formData = new FormData()
    formData.append('accion', 'eliminar')
    formData.append('id', id)

    fetch('/api/productos_admin.php', {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          alert('✅ Producto eliminado')
          setProductos(productos.filter(p => p.id !== id))
        } else {
          alert('❌ No se pudo eliminar')
        }
      })
  }

  return (
    <div>
      <h4 className="mb-4">🛒 Administración de Productos</h4>

      <button className="btn btn-primary mb-3" onClick={() => setProductoEditando({})}>
        ➕ Agregar Producto
      </button>

      <table className="table table-bordered table-hover">
        <thead className="table-success">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Grupo</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Imagen</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          {productos.map(prod => (
            <tr key={prod.id}>
              <td>{prod.id}</td>
              <td>{prod.nombre}</td>
              <td>{prod.grupo}</td>
              <td>${prod.precio}</td>
              <td>{prod.stock}</td>
              <td>
                <img src={`/multimedia/${prod.imagen || 'no-image.png'}`} style={{ width: '60px' }} />
              </td>
              <td>
                <button className="btn btn-sm btn-danger me-2" onClick={() => eliminarProducto(prod.id)}>Eliminar</button>
                <button className="btn btn-sm btn-secondary" onClick={() => setProductoEditando(prod)}>Editar</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {productoEditando && (
        <ProductForm
          producto={Object.keys(productoEditando).length ? productoEditando : null}
          onClose={() => setProductoEditando(null)}
          onSave={cargarProductos}
        />
      )}
    </div>
  )
}
