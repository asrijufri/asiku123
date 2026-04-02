import { Facebook, Instagram, Mail, Phone, MapPin } from 'lucide-react'

function Footer() {
  const currentYear = new Date().getFullYear()

  return (
    <footer className="bg-gray-900 text-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {/* About */}
          <div className="space-y-4">
            <div className="flex items-center space-x-3">
              <div className="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-lg flex items-center justify-center">
                <span className="text-2xl">🏛️</span>
              </div>
              <div>
                <h3 className="font-bold text-lg">DLHKP</h3>
                <p className="text-sm text-gray-400">Kab. Tojo Una-Una</p>
              </div>
            </div>
            <p className="text-gray-400 text-sm leading-relaxed">
              Sistem Aduan Masyarakat Dinas Lingkungan Hidup, Perumahan, Kawasan Permukiman dan Pertanahan Kabupaten Tojo Una-Una.
            </p>
          </div>

          {/* Quick Links */}
          <div>
            <h4 className="font-semibold text-lg mb-4">Tautan Cepat</h4>
            <ul className="space-y-2">
              <li><a href="/" className="text-gray-400 hover:text-white transition-colors">Beranda</a></li>
              <li><a href="/submit" className="text-gray-400 hover:text-white transition-colors">Buat Aduan</a></li>
              <li><a href="/track" className="text-gray-400 hover:text-white transition-colors">Cek Status</a></li>
              <li><a href="/complaints" className="text-gray-400 hover:text-white transition-colors">Daftar Aduan</a></li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="font-semibold text-lg mb-4">Kontak Kami</h4>
            <ul className="space-y-3">
              <li className="flex items-start space-x-3">
                <MapPin size={20} className="text-primary-400 mt-0.5" />
                <span className="text-gray-400 text-sm">Jl. Raya Trans Sulawesi, Ampana, Kab. Tojo Una-Una</span>
              </li>
              <li className="flex items-center space-x-3">
                <Phone size={20} className="text-primary-400" />
                <span className="text-gray-400 text-sm">(0458) 21234</span>
              </li>
              <li className="flex items-center space-x-3">
                <Mail size={20} className="text-primary-400" />
                <span className="text-gray-400 text-sm">dlhpkp@tojounauna.go.id</span>
              </li>
            </ul>
          </div>

          {/* Social Media */}
          <div>
            <h4 className="font-semibold text-lg mb-4">Ikuti Kami</h4>
            <div className="flex space-x-3">
              <a href="#" className="w-10 h-10 bg-gray-800 hover:bg-primary-600 rounded-lg flex items-center justify-center transition-colors">
                <Facebook size={20} />
              </a>
              <a href="#" className="w-10 h-10 bg-gray-800 hover:bg-pink-600 rounded-lg flex items-center justify-center transition-colors">
                <Instagram size={20} />
              </a>
            </div>
          </div>
        </div>

        <div className="border-t border-gray-800 mt-8 pt-8 text-center">
          <p className="text-gray-400 text-sm">
            © {currentYear} DLHKP Kabupaten Tojo Una-Una. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  )
}

export default Footer
