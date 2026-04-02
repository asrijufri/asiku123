import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { Upload, X, CheckCircle } from 'lucide-react'
import { categories } from '../data/mockData'

function SubmitComplaint() {
  const navigate = useNavigate()
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    category: '',
    title: '',
    description: '',
    location: '',
    anonymous: false
  })
  const [images, setImages] = useState([])
  const [submitted, setSubmitted] = useState(false)
  const [complaintId, setComplaintId] = useState('')

  const handleSubmit = (e) => {
    e.preventDefault()
    // Simulate submission
    const newId = `ADU-${new Date().getFullYear()}-${String(Math.floor(Math.random() * 1000)).padStart(3, '0')}`
    setComplaintId(newId)
    setSubmitted(true)
  }

  const handleImageUpload = (e) => {
    const files = Array.from(e.target.files)
    if (files.length + images.length > 5) {
      alert('Maksimal 5 gambar')
      return
    }
    
    files.forEach(file => {
      const reader = new FileReader()
      reader.onloadend = () => {
        setImages(prev => [...prev, { file, preview: reader.result }])
      }
      reader.readAsDataURL(file)
    })
  }

  const removeImage = (index) => {
    setImages(images.filter((_, i) => i !== index))
  }

  if (submitted) {
    return (
      <div className="min-h-screen bg-gray-50 pt-24 pb-12">
        <div className="max-w-2xl mx-auto px-4">
          <div className="card text-center py-12">
            <div className="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
              <CheckCircle size={40} className="text-green-600" />
            </div>
            <h2 className="text-3xl font-bold text-gray-900 mb-4">Aduan Berhasil Dikirim!</h2>
            <p className="text-gray-600 mb-6">
              Terima kasih telah berpartisipasi. Aduan Anda akan segera diproses oleh tim kami.
            </p>
            <div className="bg-gray-100 rounded-lg p-4 mb-6">
              <p className="text-sm text-gray-600 mb-2">ID Pelacakan Anda:</p>
              <p className="text-2xl font-bold text-primary-600 font-mono">{complaintId}</p>
            </div>
            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <button
                onClick={() => navigate('/track')}
                className="btn-primary"
              >
                Lacak Status
              </button>
              <button
                onClick={() => navigate('/')}
                className="btn-secondary"
              >
                Kembali ke Beranda
              </button>
            </div>
          </div>
        </div>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gray-50 pt-24 pb-12">
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-8">
          <h1 className="section-title">Buat Aduan Baru</h1>
          <p className="text-gray-600 text-lg">
            Lengkapi formulir di bawah ini untuk menyampaikan aduan Anda
          </p>
        </div>

        <form onSubmit={handleSubmit} className="card space-y-6">
          {/* Personal Information */}
          <div>
            <h3 className="text-xl font-bold text-gray-900 mb-4 flex items-center">
              <span className="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mr-3 text-sm font-bold">1</span>
              Informasi Pelapor
            </h3>
            
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Nama Lengkap <span className="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  required={!formData.anonymous}
                  disabled={formData.anonymous}
                  value={formData.name}
                  onChange={(e) => setFormData({...formData, name: e.target.value})}
                  className="input-field disabled:bg-gray-100"
                  placeholder="Masukkan nama lengkap"
                />
              </div>
              
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Email <span className="text-red-500">*</span>
                </label>
                <input
                  type="email"
                  required={!formData.anonymous}
                  disabled={formData.anonymous}
                  value={formData.email}
                  onChange={(e) => setFormData({...formData, email: e.target.value})}
                  className="input-field disabled:bg-gray-100"
                  placeholder="contoh@email.com"
                />
              </div>
              
              <div className="md:col-span-2">
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Nomor Telepon
                </label>
                <input
                  type="tel"
                  value={formData.phone}
                  onChange={(e) => setFormData({...formData, phone: e.target.value})}
                  className="input-field"
                  placeholder="08xxxxxxxxxx"
                />
              </div>
            </div>

            <div className="mt-4">
              <label className="flex items-center space-x-3 cursor-pointer">
                <input
                  type="checkbox"
                  checked={formData.anonymous}
                  onChange={(e) => setFormData({...formData, anonymous: e.target.checked})}
                  className="w-5 h-5 text-primary-600 rounded focus:ring-primary-500"
                />
                <span className="text-gray-700">Laporkan secara anonim</span>
              </label>
            </div>
          </div>

          {/* Complaint Details */}
          <div>
            <h3 className="text-xl font-bold text-gray-900 mb-4 flex items-center">
              <span className="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mr-3 text-sm font-bold">2</span>
              Detail Aduan
            </h3>
            
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Kategori <span className="text-red-500">*</span>
                </label>
                <select
                  required
                  value={formData.category}
                  onChange={(e) => setFormData({...formData, category: e.target.value})}
                  className="input-field"
                >
                  <option value="">Pilih kategori</option>
                  {categories.map(cat => (
                    <option key={cat.id} value={cat.name}>{cat.icon} {cat.name}</option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Judul Aduan <span className="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  value={formData.title}
                  onChange={(e) => setFormData({...formData, title: e.target.value})}
                  className="input-field"
                  placeholder="Ringkasan singkat aduan Anda"
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Deskripsi <span className="text-red-500">*</span>
                </label>
                <textarea
                  required
                  rows={5}
                  value={formData.description}
                  onChange={(e) => setFormData({...formData, description: e.target.value})}
                  className="input-field resize-none"
                  placeholder="Jelaskan detail aduan Anda secara lengkap..."
                />
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-2">
                  Lokasi <span className="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  required
                  value={formData.location}
                  onChange={(e) => setFormData({...formData, location: e.target.value})}
                  className="input-field"
                  placeholder="Desa/Kelurahan, Kecamatan"
                />
              </div>
            </div>
          </div>

          {/* Image Upload */}
          <div>
            <h3 className="text-xl font-bold text-gray-900 mb-4 flex items-center">
              <span className="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center mr-3 text-sm font-bold">3</span>
              Lampiran Foto (Opsional)
            </h3>
            
            <div className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-500 transition-colors">
              <input
                type="file"
                accept="image/*"
                multiple
                onChange={handleImageUpload}
                className="hidden"
                id="image-upload"
              />
              <label htmlFor="image-upload" className="cursor-pointer">
                <Upload size={40} className="mx-auto text-gray-400 mb-2" />
                <p className="text-gray-600 mb-1">Klik untuk upload atau drag & drop</p>
                <p className="text-sm text-gray-500">Maksimal 5 gambar (JPG, PNG)</p>
              </label>
            </div>

            {images.length > 0 && (
              <div className="grid grid-cols-2 md:grid-cols-5 gap-4 mt-4">
                {images.map((img, index) => (
                  <div key={index} className="relative aspect-square rounded-lg overflow-hidden bg-gray-100">
                    <img src={img.preview} alt={`Preview ${index}`} className="w-full h-full object-cover" />
                    <button
                      type="button"
                      onClick={() => removeImage(index)}
                      className="absolute top-1 right-1 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600"
                    >
                      <X size={14} />
                    </button>
                  </div>
                ))}
              </div>
            )}
          </div>

          {/* Submit Button */}
          <div className="flex flex-col sm:flex-row gap-4 pt-6 border-t">
            <button
              type="submit"
              className="btn-primary flex-1 flex items-center justify-center space-x-2"
            >
              <span>Kirim Aduan</span>
            </button>
            <button
              type="button"
              onClick={() => navigate(-1)}
              className="btn-secondary flex-1"
            >
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  )
}

export default SubmitComplaint
