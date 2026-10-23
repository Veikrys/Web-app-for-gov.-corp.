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

// Страница "Документы на нового сотрудника"
import WorkerPage from './pages/worker/Worker';

// Страницы ГГСиК — основные
import GgsikDeclarationsPage from './pages/ggsik/DeclarationsPage';
import GgsikLegalActsPage from './pages/ggsik/LegalActsPage';
import GgsikMethodsPage from './pages/ggsik/MethodsPage';
import GgsikFormsPage from './pages/ggsik/FormsPage';
import GgsikCommissionPage from './pages/ggsik/CommissionPage';

// Страницы ГГСиК — подстраницы (Нормативно-правовые акты)
import Fz230Page from './pages/ggsik/sub/Fz230Page';
import Ukaz885Page from './pages/ggsik/sub/Ukaz885Page';
import Ukaz815Page from './pages/ggsik/sub/Ukaz815Page';
import Ukaz559Page from './pages/ggsik/sub/Ukaz559Page';
import Ukaz561Page from './pages/ggsik/sub/Ukaz561Page';
import Ukaz613Page from './pages/ggsik/sub/Ukaz613Page';
import Ukaz1065Page from './pages/ggsik/sub/Ukaz1065Page';
import Ukaz460Page from './pages/ggsik/sub/Ukaz460Page';
import Ukaz297Page from './pages/ggsik/sub/Ukaz297Page';
import Ukaz821Page from './pages/ggsik/sub/Ukaz821Page';
import Postanovlenie700Page from './pages/ggsik/sub/Postanovlenie700Page';
import Prikaz1nPage from './pages/ggsik/sub/Prikaz1nPage';
import Prikaz11nPage from './pages/ggsik/sub/Prikaz11nPage';
import Prikaz9nPage from './pages/ggsik/sub/Prikaz9nPage';
import Prikaz263Page from './pages/ggsik/sub/Prikaz263Page';
import Prikaz283Page from './pages/ggsik/sub/Prikaz283Page';
import Prikaz472Page from './pages/ggsik/sub/Prikaz472Page';
import Prikaz18nPage from './pages/ggsik/sub/Prikaz18nPage';
import Prikaz6nPage from './pages/ggsik/sub/Prikaz6nPage';
import Prikaz7nPage from './pages/ggsik/sub/Prikaz7nPage';

// Страницы ГГСиК — подстраницы (Методические материалы)
import RazbMlPage from './pages/ggsik/sub/RazbMlPage';
import ProtokolPage from './pages/ggsik/sub/ProtokolPage';
import Pismo6993Page from './pages/ggsik/sub/Pismo6993Page';
import Pismo173Page from './pages/ggsik/sub/Pismo173Page';
import PamyatkaUvolPage from './pages/ggsik/sub/PamyatkaUvolPage';
import OcenkaRiskovPage from './pages/ggsik/sub/OcenkaRiskovPage';
import InformaciyaMlPage from './pages/ggsik/sub/InformaciyaMlPage';
import ObzorRekomendacijPage from './pages/ggsik/sub/ObzorRekomendacijPage';

// Страницы ГГСиК — подстраницы (Деятельность комиссии)
import Prikaz2nPage from './pages/ggsik/sub/Prikaz2nPage';
import SostavKomissiiPage from './pages/ggsik/sub/SostavKomissiiPage';
import ObrasheniePage from './pages/ggsik/sub/ObrasheniePage';
import Prikaz9nStrPage from './pages/ggsik/sub/Prikaz9nStrPage';
import Prikaz360Page from './pages/ggsik/sub/Prikaz360Page';
import Otchet2012Page from './pages/ggsik/sub/Otchet2012Page';

// Страницы Отдела РСиБИ
import {
  SecbiUcOrderPage,
  SecbiKeysOrderPage,
  SecbiDocsPage,
} from './pages/secbi';

// Страницы Административно-финансового отдела
import {
  AfoLandoksMedoPage,
  AfoTemplatesPage,
} from './pages/afo';

// Страницы Фотогалереи
import GalleryPage from './pages/gallery/GalleryPage';
import GalleryAlbumPage from './pages/gallery/GalleryAlbumPage';

// Страницы модуля опросов
import {
  PollsManagerPage,
  PollPage,
  PollEditPage,
  PollResultsPage,
} from './pages/polls';

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
            
            {/* Страница "Документы на нового сотрудника" */}
            <Route path="/worker" element={<WorkerPage />} />
            
            {/* Страницы ГГСиК — основные */}
            <Route path="/ggsik/declarations" element={<GgsikDeclarationsPage />} />
            <Route path="/ggsik/legal-acts" element={<GgsikLegalActsPage />} />
            <Route path="/ggsik/methods" element={<GgsikMethodsPage />} />
            <Route path="/ggsik/forms" element={<GgsikFormsPage />} />
            <Route path="/ggsik/commission" element={<GgsikCommissionPage />} />
            
            {/* Страницы ГГСиК — подстраницы (Нормативно-правовые акты) */}
            <Route path="/ggsik/legal-acts/fz-230" element={<Fz230Page />} />
            <Route path="/ggsik/legal-acts/ukaz-885" element={<Ukaz885Page />} />
            <Route path="/ggsik/legal-acts/ukaz-815" element={<Ukaz815Page />} />
            <Route path="/ggsik/legal-acts/ukaz-559" element={<Ukaz559Page />} />
            <Route path="/ggsik/legal-acts/ukaz-561" element={<Ukaz561Page />} />
            <Route path="/ggsik/legal-acts/ukaz-613" element={<Ukaz613Page />} />
            <Route path="/ggsik/legal-acts/ukaz-1065" element={<Ukaz1065Page />} />
            <Route path="/ggsik/legal-acts/ukaz-460" element={<Ukaz460Page />} />
            <Route path="/ggsik/legal-acts/ukaz-297" element={<Ukaz297Page />} />
            <Route path="/ggsik/legal-acts/ukaz-821" element={<Ukaz821Page />} />
            <Route path="/ggsik/legal-acts/postanovlenie-700" element={<Postanovlenie700Page />} />
            <Route path="/ggsik/legal-acts/prikaz-1n" element={<Prikaz1nPage />} />
            <Route path="/ggsik/legal-acts/prikaz-11n" element={<Prikaz11nPage />} />
            <Route path="/ggsik/legal-acts/prikaz-9n" element={<Prikaz9nPage />} />
            <Route path="/ggsik/legal-acts/prikaz-7n" element={<Prikaz7nPage />} />
            <Route path="/ggsik/legal-acts/prikaz-18n" element={<Prikaz18nPage />} />
            <Route path="/ggsik/legal-acts/prikaz-6n" element={<Prikaz6nPage />} />
            <Route path="/ggsik/legal-acts/prikaz-263" element={<Prikaz263Page />} />
            <Route path="/ggsik/legal-acts/prikaz-283" element={<Prikaz283Page />} />
            <Route path="/ggsik/legal-acts/prikaz-472" element={<Prikaz472Page />} />
            
            {/* Страницы ГГСиК — подстраницы (Методические материалы) */}
            <Route path="/ggsik/methods/razb-ml" element={<RazbMlPage />} />
            <Route path="/ggsik/methods/protokol-4-17-74" element={<ProtokolPage />} />
            <Route path="/ggsik/methods/pismo-6993-17" element={<Pismo6993Page />} />
            <Route path="/ggsik/methods/pismo-17-3-726" element={<Pismo173Page />} />
            <Route path="/ggsik/methods/pamyatka-uvolnenie" element={<PamyatkaUvolPage />} />
            <Route path="/ggsik/methods/ocenka-riskov" element={<OcenkaRiskovPage />} />
            <Route path="/ggsik/methods/informaciya-ml-2013" element={<InformaciyaMlPage />} />
            <Route path="/ggsik/methods/obzor-rekomendacij" element={<ObzorRekomendacijPage />} />
            
            {/* Страницы ГГСиК — подстраницы (Деятельность комиссии) */}
            <Route path="/ggsik/commission/prikaz-2n" element={<Prikaz2nPage />} />
            <Route path="/ggsik/commission/sostav-komissii" element={<SostavKomissiiPage />} />
            <Route path="/ggsik/commission/obrashenie" element={<ObrasheniePage />} />
            <Route path="/ggsik/commission/prikaz-9n-sluzebnaya-proverka" element={<Prikaz9nStrPage />} />
            <Route path="/ggsik/commission/prikaz-360" element={<Prikaz360Page />} />
            <Route path="/ggsik/commission/otchet-2012" element={<Otchet2012Page />} />
            
            {/* Страницы Отдела РСиБИ */}
            <Route path="/secbi/uc-order" element={<SecbiUcOrderPage />} />
            <Route path="/secbi/keys-order" element={<SecbiKeysOrderPage />} />
            <Route path="/secbi/docs" element={<SecbiDocsPage />} />
            
            {/* Страницы Административно-финансового отдела */}
            <Route path="/afo/landoks-medo" element={<AfoLandoksMedoPage />} />
            <Route path="/afo/templates" element={<AfoTemplatesPage />} />
            
            {/* Страницы Фотогалереи */}
            <Route path="/gallery" element={<GalleryPage />} />
            <Route path="/gallery/:ofk" element={<GalleryAlbumPage />} />
            
            {/* Страницы опросов */}
            <Route path="/polls/manager" element={<PollsManagerPage />} />
            <Route path="/polls/:id" element={<PollPage />} />
            <Route path="/polls/:id/edit" element={<PollEditPage />} />
            <Route path="/polls/:id/results" element={<PollResultsPage />} />

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
