import { AlertTriangle, Map, Users, Bell, ShieldCheck, BarChart3 } from 'lucide-react';
import './Features.css';

const featureList = [
  {
    icon: <AlertTriangle size={24} />,
    title: 'Real-time Reporting',
    description: 'Allow citizens to report incidents instantly from their mobile devices with precise location data.'
  },
  {
    icon: <Map size={24} />,
    title: 'Interactive Heatmaps',
    description: 'Visualize incident clusters across your jurisdiction to allocate resources effectively.'
  },
  {
    icon: <Users size={24} />,
    title: 'Multi-Agency Collaboration',
    description: 'Seamlessly coordinate between Police, Fire, and Medical services on a unified dashboard.'
  },
  {
    icon: <Bell size={24} />,
    title: 'Automated Alerts',
    description: 'Push critical alerts to field officers and citizens based on geofencing rules.'
  },
  {
    icon: <ShieldCheck size={24} />,
    title: 'Secure Evidence Chain',
    description: 'End-to-end encryption for submitted photos, videos, and witness statements.'
  },
  {
    icon: <BarChart3 size={24} />,
    title: 'Advanced Analytics',
    description: 'Generate compliance reports and identify long-term safety trends in seconds.'
  }
];

const Features = () => {
  return (
    <section id="features" className="features">
      <div className="container">
        <div className="features-header">
          <h2 className="section-title">Built for Modern Public Safety</h2>
          <p className="section-subtitle">
            Everything you need to move from reactive responses to proactive community management.
          </p>
        </div>

        <div className="features-grid">
          {featureList.map((feature, index) => (
            <div className="feature-card" key={index}>
              <div className="feature-icon-wrapper">
                {feature.icon}
              </div>
              <h3 className="feature-title">{feature.title}</h3>
              <p className="feature-description">{feature.description}</p>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Features;
