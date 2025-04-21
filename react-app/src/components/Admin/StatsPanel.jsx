import React, { useEffect, useState } from 'react'

export default function StatsPanel() {
  const [stats, setStats] = useState(null)
  const [error, setError] = useState(null)

  useEffect(() => {
    fetch('/api/stats_admin.php')
      .then(res => res.json())
      .then(data => setStats(data))
      .catch(err => {
        console.error("❌ Error al obtener estadísticas:", err)
        setError("No se pudieron cargar las estadísticas")
      })
  }, [])

  const formatoMoneda = (valor) =>
    new Intl.NumberFormat('es-AR', { style: 'currency', currency: 'ARS' }).format(valor)

  if (error) {
    return <div className="alert alert-danger">{error}</div>
  }

  if (!stats) {
    return <div className="text-muted">Cargando estadísticas...</div>
  }

  return (
    <div className="row mb-4">
      <div className="col-md-3">
        <div className="card text-white bg-success">
          <div className="card-body">
            <h5 className="card-title">Productos</h5>
            <p className="card-text fs-3">{stats.productos}</p>
          </div>
        </div>
      </div>
      <div className="col-md-3">
        <div className="card text-white bg-primary">
          <div className="card-body">
            <h5 className="card-title">Usuarios</h5>
            <p className="card-text fs-3">{stats.usuarios}</p>
          </div>
        </div>
      </div>
      <div className="col-md-3">
        <div className="card text-white bg-info">
          <div className="card-body">
            <h5 className="card-title">Facturas</h5>
            <p className="card-text fs-3">{stats.facturas}</p>
          </div>
        </div>
      </div>
      <div className="col-md-3">
        <div className="card text-white bg-dark">
          <div className="card-body">
            <h5 className="card-title">Total Ventas</h5>
            <p className="card-text fs-3">{formatoMoneda(stats.total_ventas)}</p>
          </div>
        </div>
      </div>
    </div>
  )
}
