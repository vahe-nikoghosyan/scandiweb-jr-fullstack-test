import { Routes, Route } from 'react-router-dom'
import ProductListPage from './pages/ProductListPage'
import ProductDetailsPage from './pages/ProductDetailsPage'
import './App.css'

function App() {
  return (
    <Routes>
      <Route path="/" element={<ProductListPage />} />
      <Route path="/category/:id" element={<ProductListPage />} />
      <Route path="/product/:id" element={<ProductDetailsPage />} />
    </Routes>
  )
}

export default App
