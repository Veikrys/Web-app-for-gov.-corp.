import './ImageZoomModal.css';

export default function ImageZoomModal({ image, onClose, zoom = 1 }) {
  if (!image) return null;

  const handleBackdropClick = (e) => {
    if (e.target === e.currentTarget) {
      onClose();
    }
  };

  const handleKeyDown = (e) => {
    if (e.key === 'Escape') {
      onClose();
    }
  };

  return (
    <div
      className="image-zoom-modal"
      onClick={handleBackdropClick}
      onKeyDown={handleKeyDown}
      role="dialog"
      aria-modal="true"
      aria-label="Увеличенное изображение"
    >
      <div className="image-zoom-overlay" aria-hidden="true"></div>
      <div className="image-zoom-container">
        <div className="image-zoom-wrapper" style={{ transform: `scale(${zoom})`, '--zoom': zoom }}>
          <img
            src={image.src}
            alt={image.alt}
            className="image-zoom-img"
          />
          <div className="image-zoom-btn-wrap">
            <button
              className="image-zoom-close"
              onClick={onClose}
              aria-label="Закрыть"
            >
              ✕
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
