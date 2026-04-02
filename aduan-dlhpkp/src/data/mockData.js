export const categories = [
  {
    id: 1,
    name: 'Lingkungan Hidup',
    icon: '🌿',
    description: 'Sampah, pencemaran, kerusakan lingkungan',
    color: 'from-green-500 to-emerald-600'
  },
  {
    id: 2,
    name: 'Perumahan',
    icon: '🏠',
    description: 'Hunian layak, bantuan perumahan',
    color: 'from-blue-500 to-cyan-600'
  },
  {
    id: 3,
    name: 'Kawasan Permukiman',
    icon: '🏘️',
    description: 'Infrastruktur permukiman, drainase',
    color: 'from-purple-500 to-violet-600'
  },
  {
    id: 4,
    name: 'Pertanahan',
    icon: '📜',
    description: 'Sertifikat tanah, sengketa lahan',
    color: 'from-orange-500 to-amber-600'
  }
]

export const sampleComplaints = [
  {
    id: 'ADU-2024-001',
    category: 'Lingkungan Hidup',
    title: 'Penumpukan Sampah di Jalan Raya',
    description: 'Terdapat penumpukan sampah yang tidak dikelola dengan baik di sepanjang jalan utama desa, menyebabkan bau tidak sedap dan potensi penyakit.',
    location: 'Desa Bambaremo, Kecamatan Tojo',
    status: 'proses',
    date: '2024-01-15',
    reporter: 'Anonim',
    images: []
  },
  {
    id: 'ADU-2024-002',
    category: 'Perumahan',
    title: 'Rumah Tidak Layak Huni',
    description: 'Bantuan perbaikan rumah tidak layak huni belum juga terealisasi meskipun sudah diusulkan sejak tahun lalu.',
    location: 'Kelurahan Ampana Kota',
    status: 'selesai',
    date: '2024-01-12',
    reporter: 'Budi Santoso',
    images: []
  },
  {
    id: 'ADU-2024-003',
    category: 'Kawasan Permukiman',
    title: 'Drainase Tersumbat',
    description: 'Saluran drainase lingkungan tersumbat sehingga menyebabkan genangan air saat hujan.',
    location: 'Desa Salanko, Kecamatan Una-Una',
    status: 'pending',
    date: '2024-01-10',
    reporter: 'Anonim',
    images: []
  },
  {
    id: 'ADU-2024-004',
    category: 'Pertanahan',
    title: 'Proses Sertifikat Tanah Lambat',
    description: 'Pengurusan sertifikat tanah sudah lebih dari 1 tahun namun belum juga selesai.',
    location: 'Desa Peling Tengah',
    status: 'proses',
    date: '2024-01-08',
    reporter: 'Siti Aminah',
    images: []
  },
  {
    id: 'ADU-2024-005',
    category: 'Lingkungan Hidup',
    title: 'Pencemaran Sungai',
    description: 'Aktivitas tambang mengakibatkan pencemaran sungai yang digunakan warga untuk kebutuhan sehari-hari.',
    location: 'S Desa Lebon',
    status: 'selesai',
    date: '2024-01-05',
    reporter: 'Komunitas Peduli Lingkungan',
    images: []
  }
]

export const statusColors = {
  pending: { bg: 'bg-yellow-100', text: 'text-yellow-800', label: 'Menunggu' },
  proses: { bg: 'bg-blue-100', text: 'text-blue-800', label: 'Diproses' },
  selesai: { bg: 'bg-green-100', text: 'text-green-800', label: 'Selesai' },
  ditolak: { bg: 'bg-red-100', text: 'text-red-800', label: 'Ditolak' }
}
