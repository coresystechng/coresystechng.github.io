import React, { useState } from 'react';
import logo from './Assets/img/logo.png';

export default function Header({
  navLinks = [
    { label: 'Home', href: 'https://coresystech.ng/', active: true },
    { label: 'About Us', href: 'https://coresystech.ng/aboutus.html' },
    { label: 'Clients', href: 'https://coresystech.ng/clients.html' },
    { label: 'Services', href: 'https://coresystech.ng/services.html' },
    { label: 'Contact Us', href: 'https://coresystech.ng/contactus.html' },
  ],
  cta = { label: 'Check Our FPL', href: 'https://coresystech.ng/clnsdzyleague/', external: true },
}) {
  const [open, setOpen] = useState(false);

  const toggle = () => setOpen((s) => !s);
  const close = () => setOpen(false);

  return (
    <header>
      {/* make navbar visible by default so CSS hide-by-default rule doesn't keep it hidden */}
      <nav className="navbar navbar-expand-lg py-3 fixed-top shadow-sm visible">
        <div className="container-fluid container">

          {/* Brand / logos (large and small) */}
          <div className="navbar-brand d-flex align-items-center">
            <a href="https://coresystech.ng/" className="d-inline-block" onClick={close}>
              {/* Desktop/Tablet logo */}
              <img src={logo} alt="CORE-TECH logo" style={{ width: 150 }} className="d-none d-md-block img-fluid" />
              {/* Mobile logo */}
              <img src={logo} alt="CORE-TECH logo" style={{ width: '6em' }} className="d-block d-md-none img-fluid" />
            </a>
          </div>

          {/* Toggler (controlled) */}
          <button
            className="navbar-toggler"
            type="button"
            aria-controls="navbarSupportedContent"
            aria-expanded={open}
            aria-label="Toggle navigation"
            onClick={toggle}
          >
            <span className="navbar-toggler-icon" />
          </button>

          {/* Collapsible menu */}
          <div className={`collapse navbar-collapse justify-content-center ${open ? 'show' : ''}`} id="navbarSupportedContent">
            <ul className="navbar-nav mx-auto mb-4 mb-md-0">
              {navLinks.map((link) => (
                <li className="nav-item" key={link.href}>
                  <a
                    className={`nav-link ${link.active ? 'active' : ''}`}
                    aria-current={link.active ? 'page' : undefined}
                    href={link.href}
                    onClick={close}
                  >
                    {link.label}
                  </a>
                </li>
              ))}
            </ul>

            <div className="d-flex align-items-center">
              {cta.external ? (
                <a href={cta.href} target="_blank" rel="noopener noreferrer">
                  <button className="btn glow-on-hover" type="button">{cta.label}</button>
                </a>
              ) : (
                <a href={cta.href}>
                  <button className="btn glow-on-hover" type="button">{cta.label}</button>
                </a>
              )}
            </div>
          </div>

        </div>

        <div className="nav-underline" />
      </nav>
    </header>
  );
}
