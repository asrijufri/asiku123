import { Link } from 'react-router-dom'
import { ArrowRight, CheckCircle, Clock, AlertCircle } from 'lucide-react'
import { categories, sampleComplaints, statusColors } from '../data/mockData'

function Home() {
  const stats = [
    { number: sampleComplaints.length, label: 'Total Aduan', icon: '📋' },
    { number: sampleComplaints.filter(c => c.status === 'selesai').length, label: 'Selesai', icon: '✅' },
    { number: sampleComplaints.filter(c => c.status === 'proses').length, label: 'Diproses', icon: '⚙️' },
    { number: sampleComplaints.filter(c => c.status === 'pending').length, label: 'Menunggu', icon: '⏳' }
  ]

  const recentComplaints = sampleComplaints.slice(0, 3)

  return (
    <div>
      {/* Hero Section */}
      <section className="gradient-bg text-white pt-32 pb-20 md:pt-40 md:pb-32">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center space-y-6">
            <div className="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm">
              <span>🎯</span>
              <span>Suara Anda, Perubahan Nyata</span>
            </div>
            
            <h1 className="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight">
              Sistem Aduan Masyarakat
              <br />
              <span className="text-primary-200">DLHKP Tojo Una-Una</span>
            </h1>
            
            <p className="text-lg md:text-xl text-white/90 max-w-3xl mx-auto">
              Salurkan aspirasi dan laporan Anda terkait Lingkungan Hidup, Perumahan, 
              Kawasan Permukiman, dan Pertanahan dengan mudah dan transparan.
            </p>
            
            <div className="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
              <Link to="/submit" className="btn-primary flex items-center space-x-2 text-lg px-8 py-4">
                <span>Buat Aduan Sekarang</span>
                <ArrowRight size={20} />
              </Link>
              <Link to="/complaints" className="btn-secondary flex items-center space-x-2 text-lg px-8 py-4">
                <span>Lihat Aduan Lainnya</span>
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Stats Section */}
      <section className="py-16 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            {stats.map((stat, index) => (
              <div key={index} className="card text-center hover:scale-105 transition-transform">
                <div className="text-4xl mb-2">{stat.icon}</div>
                <div className="text-3xl md:text-4xl font-bold text-gray-900">{stat.number}</div>
                <div className="text-gray-600 mt-1">{stat.label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Categories Section */}
      <section className="py-16 md:py-24 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="section-title">Kategori Aduan</h2>
            <p className="text-gray-600 text-lg max-w-2xl mx-auto">
              Pilih kategori yang sesuai dengan aduan Anda untuk memudahkan proses penanganan
            </p>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {categories.map((category) => (
              <Link
                key={category.id}
                to="/submit"
                className={`card group hover:scale-105 transition-all duration-300 border-2 border-transparent hover:border-${category.color.split('-')[1]}-200`}
              >
                <div className={`w-16 h-16 bg-gradient-to-br ${category.color} rounded-2xl flex items-center justify-center text-3xl mb-4 group-hover:scale-110 transition-transform`}>
                  {category.icon}
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-2">{category.name}</h3>
                <p className="text-gray-600 text-sm">{category.description}</p>
              </Link>
            ))}
          </div>
        </div>
      </section>

      {/* How It Works */}
      <section className="py-16 md:py-24 bg-white">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12">
            <h2 className="section-title">Cara Mengajukan Aduan</h2>
            <p className="text-gray-600 text-lg max-w-2xl mx-auto">
              Proses yang mudah dan cepat dalam 3 langkah sederhana
            </p>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              {
                step: 1,
                title: 'Isi Formulir',
                description: 'Lengkapi data dan deskripsi aduan Anda dengan jelas',
                icon: '📝'
              },
              {
                step: 2,
                title: 'Dapatkan ID',
                description: 'Simpan ID pelacakan untuk memantau status aduan',
                icon: '🔢'
              },
              {
                step: 3,
                title: 'Pantau Progress',
                description: 'Ikuti perkembangan penanganan aduan Anda secara real-time',
                icon: '📊'
              }
            ].map((item) => (
              <div key={item.step} className="relative">
                <div className="card text-center hover:scale-105 transition-transform">
                  <div className="text-5xl mb-4">{item.icon}</div>
                  <div className="absolute -top-4 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold">
                    {item.step}
                  </div>
                  <h3 className="text-xl font-bold text-gray-900 mb-2">{item.title}</h3>
                  <p className="text-gray-600">{item.description}</p>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Recent Complaints */}
      <section className="py-16 md:py-24 bg-gray-50">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
            <div>
              <h2 className="section-title">Aduan Terbaru</h2>
              <p className="text-gray-600 text-lg">Laporan yang baru saja masuk dari masyarakat</p>
            </div>
            <Link to="/complaints" className="btn-primary mt-4 md:mt-0 flex items-center space-x-2">
              <span>Lihat Semua</span>
              <ArrowRight size={18} />
            </Link>
          </div>
          
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {recentComplaints.map((complaint) => {
              const status = statusColors[complaint.status]
              return (
                <div key={complaint.id} className="card hover:shadow-xl transition-shadow">
                  <div className="flex items-start justify-between mb-3">
                    <span className="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded">
                      {complaint.id}
                    </span>
                    <span className={`px-3 py-1 rounded-full text-xs font-medium ${status.bg} ${status.text}`}>
                      {status.label}
                    </span>
                  </div>
                  <h3 className="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                    {complaint.title}
                  </h3>
                  <p className="text-gray-600 text-sm mb-4 line-clamp-2">
                    {complaint.description}
                  </p>
                  <div className="flex items-center justify-between text-sm text-gray-500">
                    <span className="flex items-center">📍 {complaint.location.split(',')[0]}</span>
                    <span>{new Date(complaint.date).toLocaleDateString('id-ID')}</span>
                  </div>
                </div>
              )
            })}
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-16 md:py-24 gradient-bg text-white">
        <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
          <h2 className="text-3xl md:text-4xl font-bold">
            Siap Menyampaikan Aduan?
          </h2>
          <p className="text-xl text-white/90">
            Partisipasi Anda sangat berarti untuk pembangunan Kabupaten Tojo Una-Una yang lebih baik
          </p>
          <Link to="/submit" className="inline-block bg-white text-primary-700 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-colors shadow-lg">
            Buat Aduan Sekarang
          </Link>
        </div>
      </section>
    </div>
  )
}

export default Home
