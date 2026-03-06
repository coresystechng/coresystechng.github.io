# CORE-TECH Site Improvement Skill
**Site:** coresystech.ng  
**Last Audited:** March 2026  
**Stack:** Static HTML/CSS/JS

Use this skill when working on the CORE-TECH website codebase. Apply the relevant section based on the task at hand.

---

## ✅ What's Already Working
- Clean navigation with dropdown services menu
- Well-organized footer with social links and quick links
- Contact details clearly listed (phone, email, address)
- Client logo section for social proof
- Services well-segmented across multiple pages

---

## 1. SEO & Meta Tags

Every page must include the following in `<head>`:

```html
<!-- Primary SEO -->
<meta name="description" content="CORE-TECH offers IT services, web development, cloud solutions, and computer training in Abuja, Nigeria.">
<meta name="keywords" content="IT services Abuja, web development Nigeria, cloud solutions SME, computer training Abuja">
<link rel="canonical" href="https://coresystech.ng/">

<!-- Open Graph (Social Sharing) -->
<meta property="og:title" content="CORE-TECH | Digital Solutions & IT Training in Nigeria">
<meta property="og:description" content="We build websites and cloud systems for Nigerian businesses.">
<meta property="og:image" content="https://coresystech.ng/assets/img/og-preview.jpg">
<meta property="og:url" content="https://coresystech.ng">
<meta property="og:type" content="website">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="CORE-TECH | Digital Solutions & IT Training">
<meta name="twitter:description" content="IT services, web development & cloud solutions in Abuja, Nigeria.">
<meta name="twitter:image" content="https://coresystech.ng/assets/img/og-preview.jpg">
```

Add LocalBusiness structured data on every page:

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "name": "CORE-TECH",
  "url": "https://coresystech.ng",
  "telephone": "+2349024364876",
  "email": "hello@coresystech.ng",
  "address": {
    "@type": "PostalAddress",
    "streetAddress": "Okitipupa Crescent, Phase IV",
    "addressLocality": "Kubwa, Abuja",
    "addressCountry": "NG"
  },
  "sameAs": [
    "https://www.instagram.com/coresystechng",
    "https://www.linkedin.com/company/coresystechng"
  ]
}
</script>
```

---

## 2. Performance & Core Web Vitals

### Image Optimization
- Convert all images from `.jpg`/`.png` to **WebP** format
- Add `loading="lazy"` to all below-the-fold images
- Always include `width` and `height` attributes to prevent Cumulative Layout Shift (CLS)

```html
<!-- Before -->
<img src="./assets/img/top notch.jpg" alt="...">

<!-- After -->
<img src="./assets/img/top-notch.webp" alt="Individual using laptop" width="800" height="500" loading="lazy">
```

### Other Performance Fixes
- Minify all CSS and JS files before deployment
- Enable Cloudflare (free tier) as CDN — works well with `.ng` domains
- Add `<link rel="preload">` for hero image and primary font
- Rename image files: replace spaces with hyphens (e.g. `top notch.jpg` → `top-notch.webp`)

---

## 3. Trust & Credibility

### Professional Email
Replace `coresystechng@gmail.com` with `hello@coresystech.ng` across all pages, contact forms, and footer.

### Testimonials Section
Add at least 3 client testimonials to the homepage above the footer:

```html
<section class="testimonials">
  <h2>What Our Clients Say</h2>
  <div class="testimonial-card">
    <p>"CORE-TECH built our website in record time and the result exceeded expectations."</p>
    <cite>— [Client Name], [Company], [Industry]</cite>
  </div>
</section>
```

### Social Proof Numbers
Add a stats bar to the homepage hero or about section:

```html
<div class="stats-bar">
  <div>50+ Projects Delivered</div>
  <div>5+ Years Experience</div>
  <div>20+ Happy Clients</div>
</div>
```

---

## 4. Hero Section

### Headline
Replace generic headline with a specific, outcome-driven one:

```html
<!-- Before -->
<h1>Enabling Digital Solutions.</h1>

<!-- After -->
<h1>We Build Websites & Cloud Systems for Nigerian Businesses</h1>
<p>From web development to IT training — CORE-TECH helps businesses in Abuja go digital, faster.</p>
```

### CTAs
Reduce to a single primary CTA on initial load:

```html
<!-- Before: two competing CTAs -->
<a href="#aboutus">Get Started</a>
<a href="#contactus">Contact Us</a>

<!-- After: one primary, one secondary -->
<a href="services.html" class="btn-primary">See Our Services</a>
<a href="#contactus" class="btn-secondary">Get in Touch</a>
```

---

## 5. FPL Section

**Remove the Fantasy Premier League (FPL) section from:**
- Homepage (`index.html`) — delete the entire FPL card/section
- Main navigation — remove the "Check Our FPL" link

The FPL content breaks professional tone for B2B visitors. Keep it accessible only at `coresystech.ng/clnsdzyleague/` directly, not linked from the main site.

---

## 6. Mobile Experience

- Ensure hamburger menu is keyboard-navigable (`tabindex`, `aria-expanded`)
- All tap targets (buttons, links) must be at least **44×44px**
- The logo appears twice in the HTML (desktop/mobile nav duplicate) — ensure only one renders at a time:

```css
.nav-logo-mobile { display: none; }

@media (max-width: 768px) {
  .nav-logo-desktop { display: none; }
  .nav-logo-mobile { display: block; }
}
```

---

## 7. Contact Form

Add client-side validation, a loading state, and a success/error message:

```html
<form id="contact-form" novalidate>
  <!-- fields -->
  <button type="submit" id="submit-btn">Send Message</button>
  <p id="form-status" aria-live="polite"></p>
</form>
```

```javascript
const form = document.getElementById('contact-form');
const status = document.getElementById('form-status');

form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const btn = document.getElementById('submit-btn');
  btn.textContent = 'Sending...';
  btn.disabled = true;

  try {
    // replace with your form handler (Formspree, Web3Forms, etc.)
    const res = await fetch('YOUR_FORM_ENDPOINT', {
      method: 'POST',
      body: new FormData(form)
    });
    if (res.ok) {
      status.textContent = '✅ Message sent! We\'ll be in touch shortly.';
      form.reset();
    } else {
      throw new Error();
    }
  } catch {
    status.textContent = '❌ Something went wrong. Please try again or email us directly.';
  } finally {
    btn.textContent = 'Send Message';
    btn.disabled = false;
  }
});
```

Recommended form backends: **Web3Forms** (free, easy) or **Formspree**.

---

## 8. Accessibility

### Client Logo Alt Text
Replace generic alt text with actual company names:

```html
<!-- Before -->
<img src="./assets/img/halibiz.png" alt="Logo 12">

<!-- After -->
<img src="./assets/img/halibiz.png" alt="Halibiz" width="120" height="60" loading="lazy">
```

### ARIA Labels
Add to icon-only or social links:

```html
<a href="https://www.instagram.com/coresystechng" aria-label="CORE-TECH on Instagram" target="_blank" rel="noopener noreferrer">
  <!-- icon -->
</a>
```

### Color Contrast
- Ensure all body text meets **WCAG AA** minimum: 4.5:1 contrast ratio
- Use https://webaim.org/resources/contrastchecker/ to verify

---

## 9. Copywriting Fixes

Apply these text corrections across the codebase:

| Location | Current Text | Correct Text |
|---|---|---|
| Clients section heading | `OUR CLIENTE` | `OUR CLIENTS` |
| Computer Training description | `the the tech world` | `the tech world` |
| Email (all pages, footer, form) | `coresystechng@gmail.com` | `hello@coresystech.ng` |

---

## 10. Analytics & Tracking

Add before closing `</head>` on every page:

```html
<!-- Google Analytics 4 -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXXXXX"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-XXXXXXXXXX');
</script>
```

**Additional setup tasks (outside codebase):**
- Create and verify site in **Google Search Console** → submit `sitemap.xml`
- Create a **Google Business Profile** for local Abuja SEO
- Generate a `sitemap.xml` and `robots.txt` if not already present

---

## 11. Medium-Term Features to Build

### Portfolio / Case Studies Page (`portfolio.html`)
Show before/after for client projects. Include: client name, industry, problem, solution, result.

### Pricing Page (`pricing.html`)
Add starting price ranges to filter serious leads. Example:
- Website Design from ₦150,000
- Cloud Setup from ₦80,000/month
- Computer Training from ₦25,000/course

### Blog (`/blog/`)
Target local SEO searches. Suggested first posts:
- "Web Development Costs in Abuja (2025 Guide)"
- "Why Nigerian SMEs Need Cloud Storage"
- "Best Computer Courses for Beginners in Abuja"

### Live Chat
Integrate **Tawk.to** (free, Nigeria-friendly):

```html
<!-- Tawk.to Live Chat -->
<script>
  var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
  (function(){
    var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
    s1.async=true;
    s1.src='https://embed.tawk.to/YOUR_TAWK_ID/default';
    s1.charset='UTF-8';
    s1.setAttribute('crossorigin','*');
    s0.parentNode.insertBefore(s1,s0);
  })();
</script>
```

---

## Quick Wins Checklist

- [ ] Fix "CLIENTE" typo → "CLIENTS"
- [ ] Fix "the the tech world" duplicate word
- [ ] Switch to professional email `hello@coresystech.ng`
- [ ] Add `<meta name="description">` to all pages
- [ ] Convert images to WebP and add `loading="lazy"`
- [ ] Remove FPL section and nav link from homepage
- [ ] Add 3 client testimonials to homepage
- [ ] Add LocalBusiness JSON-LD structured data
- [ ] Set up Google Analytics 4
- [ ] Set up Google Search Console + submit sitemap
- [ ] Fix all client logo alt text
- [ ] Add form success/error feedback states
