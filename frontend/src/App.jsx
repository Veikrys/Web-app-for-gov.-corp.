import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { ThemeProvider } from './context/ThemeContext';
import Header from './components/Header/Header';
import SeparatorBar from './components/SeparatorBar/SeparatorBar';
import LeftMenu from './components/LeftMenu/LeftMenu';
import MainContent from './components/MainContent/MainContent';
import HallOfFame from './components/HallOfFame/HallOfFame';
import Footer from './components/Footer/Footer';
import ScrollToTop from './components/ScrollToTop/ScrollToTop';
import './App.css';

// Страницы ОИС
import {
  StandardsPage,
  AsInstructionPage,
  PupePage,
  StandardRequestPage,
  ConnectionLossPage,
  OutlookPage,
  RoskaznaMailPage,
  ForumPage,
  VksPage,
  RemoteHelperPage,
  TlsCertificatePage,
  SobiEbPage,
  SobiInstructionsPage,
  SkiaoPage,
  TelephonyPage,
  EmergencyPage,
} from './pages/manuals';

function App() {
  return (
    <ThemeProvider>
      <BrowserRouter>
        <Header />
        <SeparatorBar />
        <div className="app-layout">
          <LeftMenu />
          <Routes>
            <Route path="/" element={<MainContent />} />
            
            {/* Страницы ОИС */}
            <Route path="/ois/standards" element={<StandardsPage />} />
            <Route path="/ois/as-instruction" element={<AsInstructionPage />} />
            <Route path="/ois/pupe" element={<PupePage />} />
            <Route path="/ois/standard-request" element={<StandardRequestPage />} />
            <Route path="/ois/connection-loss" element={<ConnectionLossPage />} />
            <Route path="/ois/outlook" element={<OutlookPage />} />
            <Route path="/ois/roskazna-mail" element={<RoskaznaMailPage />} />
            <Route path="/ois/forum" element={<ForumPage />} />
            <Route path="/ois/vks" element={<VksPage />} />
            <Route path="/ois/remote-helper" element={<RemoteHelperPage />} />
            <Route path="/ois/tls-certificate" element={<TlsCertificatePage />} />
            <Route path="/ois/sobi-eb" element={<SobiEbPage />} />
            <Route path="/ois/sobi-instructions" element={<SobiInstructionsPage />} />
            <Route path="/ois/skiao" element={<SkiaoPage />} />
            <Route path="/ois/telephony" element={<TelephonyPage />} />
            <Route path="/ois/emergency" element={<EmergencyPage />} />
            
            {/* Редирект с старых URL на новые */}
            <Route path="/manuals" element={<Navigate to="/ois/as-instruction" replace />} />
            <Route path="*" element={<Navigate to="/" replace />} />
          </Routes>
          <HallOfFame />
        </div>
        <ScrollToTop />
        <Footer />
      </BrowserRouter>
    </ThemeProvider>
  );
}

export default App;
