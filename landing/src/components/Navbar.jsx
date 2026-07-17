import { Shield, Menu, X } from 'lucide-react';
import { useState } from 'react';
import './Navbar.css';

const Navbar = () => {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <nav className="navbar">
      <div className="container navbar-container">
        <div className="navbar-logo">
          <Shield className="logo-icon" size={32} />
          <span className="logo-text">IncidentFlow</span>
        </div>
        
        {/* Desktop Menu */}
        <div className="navbar-menu desktop-only">
          <a href="#features" className="nav-link">Features</a>
          <a href="#about" className="nav-link">About</a>
          <button className="btn btn-outline" style={{ marginLeft: '1rem' }}>Log In</button>
          <button className="btn btn-primary" style={{ marginLeft: '0.5rem' }}>Get Started</button>
        </div>

        {/* Mobile Toggle */}
        <button className="mobile-toggle mobile-only" onClick={() => setIsOpen(!isOpen)}>
          {isOpen ? <X size={28} /> : <Menu size={28} />}
        </button>
      </div>

      {/* Mobile Menu */}
      {isOpen && (
        <div className="mobile-menu animate-fade-in">
          <a href="#features" className="mobile-link" onClick={() => setIsOpen(false)}>Features</a>
          <a href="#about" className="mobile-link" onClick={() => setIsOpen(false)}>About</a>
          <div className="mobile-actions">
            <button className="btn btn-outline full-width">Log In</button>
            <button className="btn btn-primary full-width" style={{ marginTop: '0.5rem' }}>Get Started</button>
          </div>
        </div>
      )}
    </nav>
  );
};

export default Navbar;
