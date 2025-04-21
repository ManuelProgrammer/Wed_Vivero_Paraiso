import React, { useEffect, useState } from 'react'

export default function FacturaList() {
  const [facturas, setFacturas] = useState([])

  useEffect(() => {
    fetch('/api/facturas_admin.php')
      .then(res => res.json())
      .then(data => {
        if (Array.isArray(data)) {
          setFacturas(data)
        } else {
          console.error("❌ Respuesta inesperada del servidor:", data)
        }
      })
      .catch(err => console.error("❌ Error al cargar facturas:", err))
  }, [])

  const formatearMoneda = (valor) => {
    return new Intl.NumberFormat('es-CO', {
      style: 'currency',
      currency: 'COP',
      minimumFractionDigits: 0
    }).format(valor)
  }

  return (
    <div>
      <h4 className="mb-4">📄 Lista de Facturas</h4>

      <table className="table table-bordered table-hover">
        <thead className="table-warning">
          <tr>
            <th>Nº Factura</th>
            <th>Cliente</th>
            <th>Fecha</th>
            <th>Subtotal</th>
            <th>IGV</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          {facturas.length === 0 ? (
            <tr>
              <td colSpan="6" className="text-center">No hay facturas registradas</td>
            </tr>
          ) : (
            facturas.map(factura => (
              <tr key={factura.numeroFactura}>
                <td>{factura.numeroFactura}</td>
                <td>{factura.cliente}</td>
                <td>{new Date(factura.fecha).toLocaleString()}</td>
                <td>{formatearMoneda(factura.subTotal)}</td>
                <td>{formatearMoneda(factura.igv)}</td>
                <td><strong>{formatearMoneda(factura.total)}</strong></td>
              </tr>
            ))
          )}
        </tbody>
      </table>
    </div>
  )
}
