import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import Navbar from './components/Navbar'
import Footer from './components/Footer'
import Home from './pages/Home'
import SubmitComplaint from './pages/SubmitComplaint'
import TrackComplaint from './pages/TrackComplaint'
import ComplaintList from './pages/ComplaintList'

function App() {
  return (
    <Router>
      <div className="min-h-screen bg-gray-50 flex flex-col">
        <Navbar />
        <main className="flex-grow">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/submit" element={<SubmitComplaint />} />
            <Route path="/track" element={<TrackComplaint />} />
            <Route path="/complaints" element={<ComplaintList />} />
          </Routes>
        </main>
        <Footer />
      </div>
    </Router>
  )
}

export default App
