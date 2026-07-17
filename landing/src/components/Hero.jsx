import { ArrowRight, Activity } from 'lucide-react';
import './Hero.css';

const Hero = () => {
  return (
    <section className="hero">
      <div className="hero-glow"></div>
      <div className="container hero-container">
        <div className="hero-content animate-fade-in">
          <div className="badge">
            <span className="badge-pulse"></span>
            v2.0 is now live
          </div>
          <h1 className="hero-title">
            Streamline Your <span className="text-gradient">Incident Response</span> Workflow
          </h1>
          <p className="hero-subtitle">
            A powerful, all-in-one platform for authorities and citizens to report, track, and resolve incidents in real-time. Unify your community's safety operations.
          </p>
          <div className="hero-actions">
            <button className="btn btn-primary hero-btn">
              Start Free Trial <ArrowRight size={20} />
            </button>
            <button className="btn btn-outline hero-btn">
              View Demo
            </button>
          </div>
          
          <div className="hero-stats">
            <div className="stat-item">
              <span className="stat-value">99.9%</span>
              <span className="stat-label">Uptime SLA</span>
            </div>
            <div className="stat-divider"></div>
            <div className="stat-item">
              <span className="stat-value">24/7</span>
              <span className="stat-label">Monitoring</span>
            </div>
            <div className="stat-divider"></div>
            <div className="stat-item">
              <span className="stat-value">&lt;5m</span>
              <span className="stat-label">Avg. Response Time</span>
            </div>
          </div>
        </div>
        
        <div className="hero-image-wrapper animate-fade-in" style={{ animationDelay: '0.2s' }}>
          <div className="hero-mockup">
            <div className="mockup-header">
              <div className="mockup-dots">
                <span></span><span></span><span></span>
              </div>
              <div className="mockup-url">app.incidentflow.com/dashboard</div>
            </div>
            <div className="mockup-body">
              <div className="mockup-sidebar"></div>
              <div className="mockup-content">
                <div className="mockup-card" style={{ width: '100%', height: '80px', marginBottom: '1rem' }}>
                  <div style={{ display: 'flex', alignItems: 'center', gap: '1rem', padding: '1rem' }}>
                    <div style={{ width: '40px', height: '40px', borderRadius: '50%', backgroundColor: 'rgba(244, 63, 94, 0.2)', display: 'flex', alignItems: 'center', justifyContent: 'center', color: 'var(--accent-color)' }}>
                      <Activity size={20} />
                    </div>
                    <div>
                      <div style={{ width: '120px', height: '12px', backgroundColor: 'var(--border-color)', borderRadius: '4px', marginBottom: '6px' }}></div>
                      <div style={{ width: '80px', height: '10px', backgroundColor: 'rgba(255,255,255,0.1)', borderRadius: '4px' }}></div>
                    </div>
                  </div>
                </div>
                <div style={{ display: 'flex', gap: '1rem' }}>
                  <div className="mockup-card" style={{ flex: 2, height: '200px' }}></div>
                  <div className="mockup-card" style={{ flex: 1, height: '200px' }}></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Hero;
