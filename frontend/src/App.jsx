import './App.css'
import Navbar from "./assets/Components/navbar"

export default function App() {
  return (
    <div className="min-h-screen bg-white text-[#555] font-sans">
      <Navbar />


      <main className="flex flex-col items-center justify-center pt-32 pb-20 px-6">
        <div className="max-w-4xl w-full text-center space-y-16">

          {/* le titre principal */}
          <h1 className="text-4xl md:text-5xl font-bold text-[#444] tracking-tight">
            Comprendre le logement social en France
          </h1>







          <div className="max-w-2xl mx-auto">
            <p className="text-xl font-medium leading-relaxed">
              Explorez les données clés du parc immobilier département par département grâce à nos outils d'analyse interactive.
            </p>
          </div>
          <div className="max-w-2xl mx-auto space-y-4">
            <p className="text-lg">
              L'accès au logement est un enjeu majeur de nos politiques publiques.
            </p>
            <p className="text-lg leading-relaxed">
              Cette plateforme centralise les données essentielles sur les logements sociaux, la vacance immobilière et les indicateurs socio-économiques.
            </p>
            <p className="text-lg leading-relaxed">
              Notre objectif : transformer des chiffres complexes en visualisations claires pour aider à la prise de décision.
            </p>
          </div>
          {/* Button voir qui permetra d'acceder a la page  de stat*/}
          <div className="pt-4">
            <button className="bg-[#ff6384] hover:bg-[#ff4d73] text-white font-bold py-3 px-16 rounded transition-colors duration-200">
              Voir
            </button>
          </div>
        </div>
      </main>
    </div>
  )
}