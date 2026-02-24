import logeStats from '../image/LogeStats.png'

export default function Navbar() {
  return (
    <nav className="bg-white border-b border-gray-100 px-8 py-4">
      <div className="max-w-7xl mx-auto flex items-center gap-2">
        <img src={logeStats} alt="Logo" className="h-10 w-auto" />
      </div>
    </nav>
  )
}


