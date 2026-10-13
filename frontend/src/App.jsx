import { BrowserRouter } from 'react-router-dom';
import { ThemeProvider } from './context/ThemeContext';
import Header from './components/Header/Header';
import SeparatorBar from './components/SeparatorBar/SeparatorBar';
import LeftMenu from './components/LeftMenu/LeftMenu';
import MainContent from './components/MainContent/MainContent';
import HallOfFame from './components/HallOfFame/HallOfFame';
import Footer from './components/Footer/Footer';
import ScrollToTop from './components/ScrollToTop/ScrollToTop';
import './App.css';

function App() {
  return (
    <ThemeProvider>
      <BrowserRouter>
        <Header />
        <SeparatorBar />
        <div className="app-layout">
          <LeftMenu />
          <MainContent />
          <HallOfFame />
        </div>
        <ScrollToTop />
        <Footer />
      </BrowserRouter>
    </ThemeProvider>
  );
}

export default App;
