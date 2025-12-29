# 🚨 PERFORMANCE OPTIMIZATION REQUIREMENTS

## ⚠️ NON-NEGOTIABLE REQUIREMENT

━━━━━━━━━━━━━━━━━━━━━━

🚫 **ABSOLUTE CONSTRAINT – DO NOT TOUCH VIEW OR LOGIC**

━━━━━━━━━━━━━━━━━━━━━━

During **ALL** performance and PageSpeed optimizations:

### ❌ DO NOT change any business logic
- ❌ DO NOT change any Livewire logic or behavior
- ❌ DO NOT change routes or controllers logic
- ❌ DO NOT change database queries or flow

### ❌ DO NOT change Blade views
- ❌ DO NOT change HTML structure
- ❌ DO NOT change layout, spacing, hierarchy, or order
- ❌ DO NOT change UI components
- ❌ DO NOT change animations or interactions
- ❌ DO NOT change colors, fonts, or glassmorphism style

**The site MUST look and behave EXACTLY the same.**

**Any visual or logical change is INVALID.**

---

## 🚀 GOOGLE PAGESPEED INSIGHTS (100/100 TARGET)

━━━━━━━━━━━━━━━━━━━━━━

The **ENTIRE** GroohBaz website must score:

- ✅ **100 / 100** on PageSpeed Insights (**DESKTOP**)
- ✅ **100 / 100** on PageSpeed Insights (**MOBILE**)

**This is NOT optional.**

---

## 📊 METRICS THAT MUST SCORE PERFECT

━━━━━━━━━━━━━━━━━━━━━━

- **LCP (Largest Contentful Paint)** → EXCELLENT
- **CLS (Cumulative Layout Shift)** → 0.00
- **INP** → EXCELLENT
- **TTFB** → Minimal
- **FCP** → Excellent

---

## 🧠 REQUIRED OPTIMIZATIONS (MANDATORY)

━━━━━━━━━━━━━━━━━━━━━━

You **MUST** implement **ALL** of the following:

### 🔹 LCP OPTIMIZATION:
- Preload the hero image using `<link rel="preload" as="image">`
- Ensure hero image is the LCP element
- Use compressed images (WebP preferred)
- Inline critical CSS for above-the-fold content
- Avoid background-image for LCP if possible

### 🔹 CSS OPTIMIZATION:
- Inline critical CSS in `<head>`
- Load non-critical CSS asynchronously
- Remove unused CSS
- Avoid large CSS bundles
- No render-blocking CSS

### 🔹 JAVASCRIPT OPTIMIZATION:
- **ALL JS** must be loaded with `defer` or `async`
- Remove unused JS
- Split heavy logic
- Minimize Livewire re-renders
- Use debounce/throttle for all Livewire events

### 🔹 IMAGE OPTIMIZATION:
- Serve images from `public/`
- Use proper `width` & `height` attributes
- Lazy-load all below-the-fold images
- Use responsive images (`srcset` if possible)
- No oversized images

### 🔹 FONT OPTIMIZATION:
- Use system fonts **OR**
- Preload custom fonts
- Use `font-display: swap`
- Avoid loading multiple font weights

### 🔹 LAYOUT STABILITY:
- Reserve space for images and ads
- Avoid layout shifts on load
- Use skeleton loaders for Livewire content

---

## 📱 MOBILE-FIRST REQUIREMENTS

━━━━━━━━━━━━━━━━━━━━━━

- Mobile performance is **PRIORITY**
- Touch-optimized UI
- No blocking scripts on mobile
- Minimal DOM depth
- Avoid heavy animations on mobile

---

## 🧪 VALIDATION REQUIREMENT

━━━━━━━━━━━━━━━━━━━━━━

The final output **MUST**:

- Score **100/100** on PageSpeed Insights
- Pass Lighthouse audits
- Have **ZERO warnings** in:
  - Performance
  - Best Practices
  - SEO
  - Accessibility (minimum 95+)

---

## 🚨 FAILURE CONDITION

━━━━━━━━━━━━━━━━━━━━━━

If PageSpeed score is below **100** on **ANY** page:

- You **MUST** refactor and optimize further
- Until **100/100** is achieved

---

## 🔥 FINAL PERFORMANCE RESULT MUST:

━━━━━━━━━━━━━━━━━━━━━━

- Instant load feeling
- Perfect Lighthouse score
- No render-blocking resources
- Elite-level performance

---

**Last Updated:** 2025-01-27  
**Status:** ACTIVE - MANDATORY REQUIREMENT

