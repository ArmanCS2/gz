/**
 * Performance Optimizations for Scroll and Animation Smoothness
 * 
 * This file contains additional performance optimizations that improve
 * scroll smoothness and animation performance across the website.
 */

(function() {
    'use strict';

    // Throttle function for scroll/resize events
    function throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }

    // Debounce function for expensive operations
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Optimize image loading - use Intersection Observer for lazy loading
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute('data-src');
                        observer.unobserve(img);
                    }
                }
            });
        }, {
            rootMargin: '50px' // Start loading 50px before image enters viewport
        });

        // Observe all images with data-src attribute
        document.querySelectorAll('img[data-src]').forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Optimize scroll-based animations with Intersection Observer
    if ('IntersectionObserver' in window) {
        const animationObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        // Observe elements that should animate on scroll
        document.querySelectorAll('.glass-card, .blog-card').forEach(el => {
            animationObserver.observe(el);
        });
    }

    // Prevent layout thrashing by batching DOM reads/writes
    const rafQueue = [];
    let rafScheduled = false;

    function scheduleRAF(callback) {
        rafQueue.push(callback);
        if (!rafScheduled) {
            rafScheduled = true;
            requestAnimationFrame(() => {
                rafScheduled = false;
                const queue = rafQueue.slice();
                rafQueue.length = 0;
                queue.forEach(cb => cb());
            });
        }
    }

    // Expose utility functions globally for use in other scripts
    window.performanceUtils = {
        throttle,
        debounce,
        scheduleRAF
    };

    // Optimize CSS animations - disable on low-end devices
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.style.setProperty('--animation-duration', '0.01ms');
    }

    // Detect if device is low-end and reduce animations
    const isLowEndDevice = navigator.hardwareConcurrency <= 2 || 
                          (navigator.deviceMemory && navigator.deviceMemory <= 2);
    
    if (isLowEndDevice) {
        // Reduce backdrop-filter blur on low-end devices
        const style = document.createElement('style');
        style.textContent = `
            .navbar-modern,
            .glass-card {
                backdrop-filter: blur(5px) !important;
                -webkit-backdrop-filter: blur(5px) !important;
            }
        `;
        document.head.appendChild(style);
    }
})();

