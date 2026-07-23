import { Shield, Mail, Globe, Phone } from 'lucide-react';
import './Footer.css';

const Footer = () => {
  return (
    <footer className="footer">
      <div className="container">
        <div className="footer-grid">
          <div className="footer-brand">
            <div className="footer-logo">
              <Shield className="logo-icon" size={24} />
              <span className="logo-text">IncidentFlow</span>
            </div>
            <p className="footer-description">
              Empowering communities with next-generation safety and incident management tools.
            </p>
            <div className="social-links">
              <a href="#" className="social-link"><Globe size={20} /></a>
              <a href="#" className="social-link"><Mail size={20} /></a>
              <a href="#" className="social-link"><Phone size={20} /></a>
            </div>
          </div>
          
          <div className="footer-links-group">
            <h4 className="footer-heading">Product</h4>
            <a href="#" className="footer-link">Features</a>
            <a href="#" className="footer-link">Pricing</a>
            <a href="#" className="footer-link">Integrations</a>
            <a href="#" className="footer-link">Changelog</a>
          </div>
          
          <div className="footer-links-group">
            <h4 className="footer-heading">Resources</h4>
            <a href="#" className="footer-link">Documentation</a>
            <a href="#" className="footer-link">API Reference</a>
            <a href="#" className="footer-link">Blog</a>
            <a href="#" className="footer-link">Community</a>
          </div>
          
          <div className="footer-links-group">
            <h4 className="footer-heading">Company</h4>
            <a href="#" className="footer-link">About Us</a>
            <a href="#" className="footer-link">Careers</a>
            <a href="#" className="footer-link">Privacy Policy</a>
            <a href="#" className="footer-link">Terms of Service</a>
          </div>
        </div>
        
        <div className="footer-bottom">
          <p>&copy; {new Date().getFullYear()} IncidentFlow. All rights reserved.</p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
