import React from 'react';
import logo from './Assets/img/logo.png';

export default function Footer({
  companyName = 'CORE-TECH',
  tagline = 'Enabling Digital Solutions for you and your business.',
  contactHref = 'https://coresystech.ng/contactus.html',
  links = [
    { label: 'Home', href: 'https://coresystech.ng' },
    { label: 'About Us', href: 'https/://coresystech.ng/aboutus.html' },
    { label: 'Clients', href: 'https://coresystech.ng/clients.html' },
    { label: 'Services', href: 'https://coresystech.ng/services.html' },
  ],
  resources = [
    { label: 'Github', href: 'https://github.com/coresystechng' },
    { label: 'Portfolio', href: 'https://coresystech.ng/aboutus.html' },
    { label: 'Terms of Use', href: 'https://coresystech.ng/terms.html' },
    { label: 'Privacy Policy', href: 'https://coresystech.ng/privacy_policy.html' },
  ],
  socials = [
    { label: 'X (Formerly Twitter)', href: 'https://www.x.com/@coresystechng' },
    { label: 'Instagram', href: 'https://www.instagram.com/coresystechng' },
    { label: 'Facebook', href: 'https://web.facebook.com/coresystemstech' },
    { label: 'LinkedIn', href: 'https://www.linkedin.com/in/collins-okoroafor-60732b12a/' },
  ],
}) {
  const year = new Date().getFullYear();

  return (
    <footer>
      <section className="footer-section bg-light py-5">
        <div className="container">
          <div className="row gx-1 align-items-center">
            <div className="col-md-6">
              <div className="footer-title mb-3">
                <img src={logo} alt="CORE-TECH logo" style={{ width: 150 }} className="img-fluid" />
              </div>

              <p className="footer-text">{tagline}</p>

              <div className="mb-4">
                <a href={contactHref} className="btn btn-secondary">Contact Us Today</a>
              </div>

              <div className="footer-credit mb-4 mb-md-0">
                &copy; {year} <span className="bluey">{companyName}</span>. All Rights Reserved.
              </div>
            </div>

            <div className="col-md-6 text-md-end mt-4 mt-md-0">
              <div className="row text-md-end">
                <div className="col-sm-12 col-md-4 footer-nav mb-4 mb-sm-0 text-start">
                  <h6>Quick Links</h6>
                  <ul className="list-unstyled mb-0">
                    {links.map(l => <li key={l.href}><a href={l.href}>{l.label}</a></li>)}
                  </ul>
                </div>

                <div className="col-sm-12 col-md-4 footer-nav mb-4 mb-sm-0 text-start">
                  <h6>Resources</h6>
                  <ul className="list-unstyled mb-0">
                    {resources.map(s => <li key={s.href}><a href={s.href} target="_blank" rel="noopener noreferrer">{s.label}</a></li>)}
                  </ul>
                </div>

                <div className="col-sm-12 col-md-4 footer-nav mb-4 mb-sm-0 text-start">
                  <h6>Socials</h6>
                  <ul className="list-unstyled mb-0">
                    {socials.map(s => <li key={s.href}><a href={s.href} target="_blank" rel="noopener noreferrer">{s.label}</a></li>)}
                  </ul>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>
    </footer>
  );
}
