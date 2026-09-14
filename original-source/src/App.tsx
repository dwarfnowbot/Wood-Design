import { BrowserRouter, Routes, Route } from "react-router-dom";
import Layout from "./components/Layout";
import Home from "./pages/Home";
import About from "./pages/About";
import Kitchens from "./pages/Kitchens";
import Wardrobes from "./pages/Wardrobes";
import InteriorWoodwork from "./pages/InteriorWoodwork";
import Projects from "./pages/Projects";
import MaterialsFinishes from "./pages/MaterialsFinishes";
import Process from "./pages/Process";
import GetQuote from "./pages/GetQuote";
import Contact from "./pages/Contact";
import NotFound from "./pages/NotFound";

export default function App() {
  return (
    <BrowserRouter>
      <Layout>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/about" element={<About />} />
          <Route path="/kitchens" element={<Kitchens />} />
          <Route path="/wardrobes" element={<Wardrobes />} />
          <Route path="/interior-woodwork" element={<InteriorWoodwork />} />
          <Route path="/projects" element={<Projects />} />
          <Route path="/materials-finishes" element={<MaterialsFinishes />} />
          <Route path="/process" element={<Process />} />
          <Route path="/get-a-quote" element={<GetQuote />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="*" element={<NotFound />} />
        </Routes>
      </Layout>
    </BrowserRouter>
  );
}
