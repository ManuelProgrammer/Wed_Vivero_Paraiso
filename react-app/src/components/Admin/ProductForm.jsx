import React, { useState, useEffect } from 'react'

export default function ProductForm({ producto, onClose, onSave }) {
  const [formData, setFormData] = useState({
    id: '',
    nombre: '',
    grupo: '',
    subGrupo: '',
    descripcion: '',
    precio: '',
    stock: '',
    imagen: null,
    destacado: 0
  })

  const [previewImage, setPreviewImage] = useState(null)
  const modoEdicion = !!producto

  const gruposYSubgrupos = {
    'Plantas': ['Ornamentales de Interior', 'Ornamentales de Exterior', 'Trepadoras', 'Arbustos Ornamentales', 'Maceta', 'Colgantes'],
    'Suculentas': ['Suculentas de Sol', 'Suculentas de Sombra', 'Mini Suculentas', 'Cactus', 'Arreglos con Suculentas'],
    'Plantas Medicinales': ['Aromáticas', 'Terapéuticas', 'Comestibles'],
    'Fertilizantes': ['Orgánicos', 'Químicos', 'Líquidos', 'Granulados', 'Para flores', 'Para césped'],
    'Abonos': ['Humus de lombriz', 'Compost', 'Estiércol', 'Abonos foliares', 'Mezclas para macetas'],
    'Materas': ['Plásticas', 'Barro', 'Decorativas', 'Colgantes', 'Autorriego'],
    'Herramientas de Jardinería': ['Palas y rastrillos', 'Guantes', 'Tijeras de poda', 'Regaderas', 'Kits de jardinería']
  }

  useEffect(() => {
    if (producto) {
      setFormData({
        id: producto.id || '',
        nombre: producto.nombre || '',
        grupo: producto.grupo || '',
        subGrupo: producto.subGrupo || '',
        descripcion: producto.descripcion || '',
        precio: producto.precio || '',
        stock: producto.stock || '',
        imagen: null,
        destacado: producto.destacado || 0
      })
      if (producto.imagen) {
        setPreviewImage(`/multimedia/${producto.imagen}`)
      }
    }
  }, [producto])

  const handleChange = (e) => {
    const { name, value, type, checked } = e.target
    setFormData(prev => ({
      ...prev,
      [name]: type === 'checkbox' ? (checked ? 1 : 0) : value
    }))
  }

  const handleFileChange = (e) => {
    const file = e.target.files[0]
    if (file) {
      setFormData(prev => ({ ...prev, imagen: file }))
      setPreviewImage(URL.createObjectURL(file))
    }
  }

  const handleSubmit = (e) => {
    e.preventDefault()

    const data = new FormData()
    data.append('accion', modoEdicion ? 'actualizar' : 'crear')
    if (modoEdicion) data.append('id', formData.id)
    data.append('nombre', formData.nombre)
    data.append('grupo', formData.grupo)
    data.append('subGrupo', formData.subGrupo)
    data.append('descripcion', formData.descripcion)
    data.append('precio', formData.precio)
    data.append('stock', formData.stock)
    data.append('destacado', formData.destacado)
    if (formData.imagen) data.append('imagen', formData.imagen)

    fetch('/api/productos_admin.php', {
      method: 'POST',
      body: data
    })
      .then(res => res.json())
      .then(result => {
        if (result.success) {
          alert(`✅ Producto ${modoEdicion ? 'actualizado' : 'agregado'} correctamente`)
          onSave()
          onClose()
        } else {
          alert('❌ Error: ' + (result.error || 'Error desconocido'))
        }
      })
      .catch(error => {
        console.error('❌ Error al guardar producto:', error)
        alert('❌ Error de red al guardar el producto')
      })
  }

  return (
    <div className="modal show d-block" style={{ backgroundColor: '#00000088' }}>
      <div className="modal-dialog">
        <div className="modal-content">
          <form onSubmit={handleSubmit}>
            <div className="modal-header">
              <h5 className="modal-title">{modoEdicion ? 'Editar' : 'Agregar'} Producto</h5>
              <button type="button" className="btn-close" onClick={onClose}></button>
            </div>
            <div className="modal-body">
              {/* campos */}
              <input name="nombre" className="form-control mb-2" placeholder="Nombre" value={formData.nombre} onChange={handleChange} required />
              <select name="grupo" className="form-control mb-2" value={formData.grupo} onChange={handleChange} required>
                <option value="">Seleccione grupo</option>
                {Object.keys(gruposYSubgrupos).map(g => <option key={g} value={g}>{g}</option>)}
              </select>
              {formData.grupo && (
                <select name="subGrupo" className="form-control mb-2" value={formData.subGrupo} onChange={handleChange} required>
                  <option value="">Seleccione subgrupo</option>
                  {gruposYSubgrupos[formData.grupo].map(s => <option key={s} value={s}>{s}</option>)}
                </select>
              )}
              <textarea name="descripcion" className="form-control mb-2" placeholder="Descripción" value={formData.descripcion} onChange={handleChange}></textarea>
              <input type="number" name="precio" className="form-control mb-2" placeholder="Precio" value={formData.precio} onChange={handleChange} required />
              <input type="number" name="stock" className="form-control mb-2" placeholder="Stock" value={formData.stock} onChange={handleChange} required />
              <div className="form-check mb-2">
                <input className="form-check-input" type="checkbox" name="destacado" id="destacado" checked={!!formData.destacado} onChange={handleChange} />
                <label className="form-check-label" htmlFor="destacado">Destacado</label>
              </div>
              <input type="file" name="imagen" className="form-control" accept="image/*" onChange={handleFileChange} />
              {previewImage && <img src={previewImage} className="img-fluid mt-2 border" alt="Vista previa" style={{ maxHeight: 150 }} />}
            </div>
            <div className="modal-footer">
              <button type="submit" className="btn btn-success">Guardar</button>
              <button type="button" className="btn btn-secondary" onClick={onClose}>Cancelar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  )
}
