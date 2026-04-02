import { useState } from 'react'
import { Search, AlertCircle } from 'lucide-react'
import { sampleComplaints, statusColors } from '../data/mockData'

function TrackComplaint() {
  const [searchId, setSearchId] = useState('')
  const [result, setResult] = useState(null)
  const [error, setError] = useState(false)

  const handleSearch = (e) => {
    e.preventDefault()
    const complaint = sampleComplaints.find(c => c.id === searchId.toUpperCase())
    
    if (complaint) {
      setResult(complaint)
      setError(false)
    } else {
      setResult(null)
      setError(true)
    }
  }

  return (
    <div className="min-h-screen bg-gray-50 pt-24 pb-12">
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-8">
          <h1 className="section-title">Cek Status Aduan</h1>
          <p className="text-gray-600 text-lg">
            Masukkan ID pelacakan untuk melihat status aduan Anda
          </p>
        </div>

        {/* Search Form */}
        <div className="card mb-8">
          <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-4">
            <div className="flex-grow">
              <input
                type="text"
                value={searchId}
                onChange={(e) => setSearchId(e.target.value)}
                placeholder="Masukkan ID Aduan (contoh: ADU-2024-001)"
                className="input-field font-mono uppercase"
              />
            </div>
            <button type="submit" className="btn-primary flex items-center justify-center space-x-2">
              <Search size={20} />
              <span>Cari</span>
            </button>
          </form>
        </div>

        {/* Error Message */}
        {error && (
          <div className="bg-red-50 border border-red-200 rounded-lg p-4 mb-8 flex items-start space-x-3">
            <AlertCircle className="text-red-600 mt-0.5" size={20} />
            <div>
              <h3 className="font-semibold text-red-900">Aduan Tidak Ditemukan</h3>
              <p className="text-red-700 text-sm mt-1">
                ID yang Anda masukkan tidak valid atau tidak ditemukan. Silakan periksa kembali ID pelacakan Anda.
              </p>
            </div>
          </div>
        )}

        {/* Result */}
        {result && (
          <div className="card">
            <div className="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-6 border-b">
              <div>
                <div className="flex items-center space-x-3 mb-2">
                  <span className="text-2xl font-bold text-primary-600 font-mono">{result.id}</span>
                  <span className={`px-3 py-1 rounded-full text-sm font-medium ${statusColors[result.status].bg} ${statusColors[result.status].text}`}>
                    {statusColors[result.status].label}
                  </span>
                </div>
                <p className="text-gray-600 text-sm">Dilaporkan pada {new Date(result.date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</p>
              </div>
              <div className="mt-4 md:mt-0">
                <span className="inline-flex items-center space-x-2 text-gray-600">
                  <span>📂</span>
                  <span>{result.category}</span>
                </span>
              </div>
            </div>

            <div className="space-y-6">
              <div>
                <h3 className="text-xl font-bold text-gray-900 mb-2">{result.title}</h3>
                <p className="text-gray-600 leading-relaxed">{result.description}</p>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="bg-gray-50 rounded-lg p-4">
                  <h4 className="font-semibold text-gray-700 mb-2">📍 Lokasi</h4>
                  <p className="text-gray-600">{result.location}</p>
                </div>
                <div className="bg-gray-50 rounded-lg p-4">
                  <h4 className="font-semibold text-gray-700 mb-2">👤 Pelapor</h4>
                  <p className="text-gray-600">{result.reporter}</p>
                </div>
              </div>

              {/* Timeline */}
              <div className="border-t pt-6">
                <h4 className="font-bold text-gray-900 mb-4">Timeline Progress</h4>
                <div className="space-y-4">
                  <div className="flex items-start space-x-4">
                    <div className="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                      <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                      </svg>
                    </div>
                    <div>
                      <p className="font-medium text-gray-900">Aduan Diterima</p>
                      <p className="text-sm text-gray-600">{new Date(result.date).toLocaleDateString('id-ID')}</p>
                    </div>
                  </div>
                  
                  {(result.status === 'proses' || result.status === 'selesai') && (
                    <div className="flex items-start space-x-4">
                      <div className="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                      </div>
                      <div>
                        <p className="font-medium text-gray-900">Sedang Diproses</p>
                        <p className="text-sm text-gray-600">{new Date(new Date(result.date).getTime() + 86400000).toLocaleDateString('id-ID')}</p>
                      </div>
                    </div>
                  )}
                  
                  {result.status === 'selesai' && (
                    <div className="flex items-start space-x-4">
                      <div className="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg className="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                      <div>
                        <p className="font-medium text-gray-900">Selesai Ditangani</p>
                        <p className="text-sm text-gray-600">{new Date(new Date(result.date).getTime() + 259200000).toLocaleDateString('id-ID')}</p>
                      </div>
                    </div>
                  )}
                </div>
              </div>
            </div>
          </div>
        )}

        {/* Sample IDs */}
        {!result && !error && (
          <div className="card bg-blue-50 border-blue-200">
            <h3 className="font-semibold text-blue-900 mb-3">💡 Contoh ID Pelacakan</h3>
            <p className="text-blue-700 text-sm mb-3">Gunakan salah satu ID berikut untuk mencoba fitur pelacakan:</p>
            <div className="flex flex-wrap gap-2">
              {sampleComplaints.slice(0, 3).map(complaint => (
                <button
                  key={complaint.id}
                  onClick={() => setSearchId(complaint.id)}
                  className="px-3 py-2 bg-white border border-blue-200 rounded-lg text-sm font-mono text-blue-700 hover:bg-blue-100 transition-colors"
                >
                  {complaint.id}
                </button>
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  )
}

export default TrackComplaint
