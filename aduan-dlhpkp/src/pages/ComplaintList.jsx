import { useState } from 'react'
import { Search, Filter, MapPin, Calendar, User } from 'lucide-react'
import { sampleComplaints, statusColors, categories } from '../data/mockData'

function ComplaintList() {
  const [searchTerm, setSearchTerm] = useState('')
  const [selectedCategory, setSelectedCategory] = useState('')
  const [selectedStatus, setSelectedStatus] = useState('')

  const filteredComplaints = sampleComplaints.filter(complaint => {
    const matchesSearch = complaint.title.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         complaint.description.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         complaint.id.toLowerCase().includes(searchTerm.toLowerCase())
    const matchesCategory = !selectedCategory || complaint.category === selectedCategory
    const matchesStatus = !selectedStatus || complaint.status === selectedStatus
    
    return matchesSearch && matchesCategory && matchesStatus
  })

  return (
    <div className="min-h-screen bg-gray-50 pt-24 pb-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {/* Header */}
        <div className="mb-8">
          <h1 className="section-title">Daftar Aduan Masyarakat</h1>
          <p className="text-gray-600 text-lg">
            Transparansi penanganan aduan dari masyarakat Kabupaten Tojo Una-Una
          </p>
        </div>

        {/* Filters */}
        <div className="card mb-8">
          <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div className="md:col-span-2">
              <div className="relative">
                <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" size={20} />
                <input
                  type="text"
                  value={searchTerm}
                  onChange={(e) => setSearchTerm(e.target.value)}
                  placeholder="Cari berdasarkan judul, deskripsi, atau ID..."
                  className="input-field pl-10"
                />
              </div>
            </div>
            
            <div>
              <select
                value={selectedCategory}
                onChange={(e) => setSelectedCategory(e.target.value)}
                className="input-field"
              >
                <option value="">Semua Kategori</option>
                {categories.map(cat => (
                  <option key={cat.id} value={cat.name}>{cat.name}</option>
                ))}
              </select>
            </div>
            
            <div>
              <select
                value={selectedStatus}
                onChange={(e) => setSelectedStatus(e.target.value)}
                className="input-field"
              >
                <option value="">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="proses">Diproses</option>
                <option value="selesai">Selesai</option>
                <option value="ditolak">Ditolak</option>
              </select>
            </div>
          </div>

          {/* Active Filters */}
          {(selectedCategory || selectedStatus || searchTerm) && (
            <div className="flex items-center space-x-2 mt-4 pt-4 border-t">
              <Filter size={16} className="text-gray-500" />
              <span className="text-sm text-gray-600">Filter aktif:</span>
              {searchTerm && (
                <span className="px-3 py-1 bg-primary-100 text-primary-700 rounded-full text-sm">
                  "{searchTerm}"
                </span>
              )}
              {selectedCategory && (
                <span className="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
                  {selectedCategory}
                </span>
              )}
              {selectedStatus && (
                <span className="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                  {statusColors[selectedStatus].label}
                </span>
              )}
              <button
                onClick={() => {
                  setSearchTerm('')
                  setSelectedCategory('')
                  setSelectedStatus('')
                }}
                className="text-sm text-red-600 hover:text-red-700 font-medium"
              >
                Reset
              </button>
            </div>
          )}
        </div>

        {/* Results Count */}
        <div className="mb-6 flex items-center justify-between">
          <p className="text-gray-600">
            Menampilkan <span className="font-semibold text-gray-900">{filteredComplaints.length}</span> dari{' '}
            <span className="font-semibold text-gray-900">{sampleComplaints.length}</span> aduan
          </p>
        </div>

        {/* Complaint List */}
        {filteredComplaints.length > 0 ? (
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {filteredComplaints.map((complaint) => {
              const status = statusColors[complaint.status]
              const category = categories.find(c => c.name === complaint.category)
              
              return (
                <div key={complaint.id} className="card hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                  <div className="flex items-start justify-between mb-3">
                    <span className="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">
                      {complaint.id}
                    </span>
                    <span className={`px-3 py-1 rounded-full text-xs font-medium ${status.bg} ${status.text}`}>
                      {status.label}
                    </span>
                  </div>
                  
                  <div className="flex items-center space-x-2 mb-3">
                    <div className={`w-8 h-8 bg-gradient-to-br ${category?.color} rounded-lg flex items-center justify-center text-lg`}>
                      {category?.icon}
                    </div>
                    <span className="text-sm font-medium text-gray-600">{complaint.category}</span>
                  </div>
                  
                  <h3 className="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                    {complaint.title}
                  </h3>
                  
                  <p className="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                    {complaint.description}
                  </p>
                  
                  <div className="space-y-2 pt-4 border-t">
                    <div className="flex items-center text-sm text-gray-500">
                      <MapPin size={16} className="mr-2" />
                      <span className="truncate">{complaint.location}</span>
                    </div>
                    <div className="flex items-center text-sm text-gray-500">
                      <Calendar size={16} className="mr-2" />
                      <span>{new Date(complaint.date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })}</span>
                    </div>
                    <div className="flex items-center text-sm text-gray-500">
                      <User size={16} className="mr-2" />
                      <span>{complaint.reporter}</span>
                    </div>
                  </div>
                </div>
              )
            })}
          </div>
        ) : (
          <div className="card text-center py-12">
            <div className="text-6xl mb-4">🔍</div>
            <h3 className="text-xl font-bold text-gray-900 mb-2">Tidak Ada Aduan Ditemukan</h3>
            <p className="text-gray-600 mb-6">
              Coba ubah filter pencarian Anda atau ajukan aduan baru
            </p>
            <button
              onClick={() => {
                setSearchTerm('')
                setSelectedCategory('')
                setSelectedStatus('')
              }}
              className="btn-primary"
            >
              Reset Filter
            </button>
          </div>
        )}
      </div>
    </div>
  )
}

export default ComplaintList
