import { Routes, Route } from "react-router-dom";
import Header from "./components/layout/Header/Header";
import ProductListPage from "./components/pages/ProductListPage/ProductListPage";
import ProductDetailsPage from "./components/pages/ProductDetailsPage/ProductDetailsPage";
import "./App.css";

function App() {
  return (
    <>
      <Header />
      <Routes>
        <Route path="/" element={<ProductListPage />} />
        <Route path="/category/:id" element={<ProductListPage />} />
        <Route path="/product/:id" element={<ProductDetailsPage />} />
      </Routes>
    </>
  );
}

export default App;
