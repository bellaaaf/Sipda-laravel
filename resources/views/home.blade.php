@extends('layouts.app')

@section('title', 'Beranda')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

<style>
/* ═══════════════════════════════════════════════
   DESIGN TOKENS — LIGHT & DARK THEME
═══════════════════════════════════════════════ */
:root {
    /* LIGHT MODE (DEFAULT) - Abu-abu terang */
    --c-bg:        #F1F2F6;
    --c-bg2:       #E8EAF0;
    --c-surface:   #FFFFFF;
    --c-surface2:  #F7F8FA;
    --c-border:    #E2E5EC;
    --c-border2:   #D1D5E0;
    --c-text:      #1A1D26;
    --c-muted:     #6B7280;
    --c-muted2:    #9CA3AF;
    --c-darurat:   #DC2626;
    --c-siaga:     #3B82F6;
    --c-waspada:   #F59E0B;
    --c-aman:      #10B981;
    --c-update:    #8B5CF6;
    --font-display:'Space Grotesk', sans-serif;
    --font-body:   'Inter', sans-serif;
    --r-card:      16px;
    --r-btn:       12px;
    --shadow-sm:   0 1px 3px rgba(0,0,0,.06), 0 0 0 1px rgba(0,0,0,.03);
    --shadow-md:   0 4px 12px rgba(0,0,0,.08), 0 0 0 1px rgba(0,0,0,.04);
    --shadow-lg:   0 12px 32px rgba(0,0,0,.10), 0 0 0 1px rgba(0,0,0,.04);
    --shadow-xl:   0 20px 48px rgba(0,0,0,.14);
    --ticker-bg:   #111827;
    --cta-bg:      #111827;
    --map-filter:  saturate(.85) brightness(1.02);
}

/* DARK MODE - Hitam pekat (bukan biru tua) */
[data-theme="dark"] {
    --c-bg:        #0D0E12;
    --c-bg2:       #14161C;
    --c-surface:   #1A1D26;
    --c-surface2:  #222631;
    --c-border:    #2C313E;
    --c-border2:   #3A4050;
    --c-text:      #E8EDF5;
    --c-muted:     #9AA3B8;
    --c-muted2:    #6B758A;
    --shadow-sm:   0 1px 3px rgba(0,0,0,.4), 0 0 0 1px rgba(255,255,255,.04);
    --shadow-md:   0 4px 12px rgba(0,0,0,.5), 0 0 0 1px rgba(255,255,255,.04);
    --shadow-lg:   0 12px 32px rgba(0,0,0,.6), 0 0 0 1px rgba(255,255,255,.04);
    --shadow-xl:   0 20px 48px rgba(0,0,0,.7);
    --ticker-bg:   #080A0E;
    --cta-bg:      #080A0E;
    --map-filter:  saturate(.5) brightness(.65);
}

body {
    font-family: var(--font-body);
    background: var(--c-bg);
    color: var(--c-text);
    transition: background .3s ease, color .3s ease;
}

/* ═══════════════════════════════════════════════
   DARK MODE TOGGLE BUTTON
═══════════════════════════════════════════════ */
.theme-toggle {
    position: fixed;
    bottom: 24px;
    right: 24px;
    z-index: 9999;
    width: 52px;
    height: 52px;
    border-radius: 50%;
    background: var(--c-surface);
    border: 2px solid var(--c-border);
    box-shadow: var(--shadow-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .3s cubic-bezier(.16,1,.3,1);
    color: var(--c-text);
}
.theme-toggle:hover {
    transform: scale(1.1);
    box-shadow: var(--shadow-xl);
    border-color: var(--c-siaga);
}
.theme-toggle:active {
    transform: scale(.95);
}
.theme-toggle .material-symbols-outlined {
    font-size: 26px;
    font-variation-settings: 'FILL' 1;
    transition: transform .3s ease;
}
.theme-toggle:hover .material-symbols-outlined {
    transform: rotate(30deg);
}

/* ═══════════════════════════════════════════════
   CUSTOM TAWK.TO STYLING
═══════════════════════════════════════════════ */
.tawk-min-container { z-index: 9998 !important; }
.tawk-button-circle {
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.4) !important;
    transition: transform 0.2s ease !important;
}
.tawk-button-circle:hover { transform: scale(1.08) !important; }
.tawk-chat-bubble { background: linear-gradient(135deg, #3B82F6, #2563EB) !important; }
.tawk-header { background: linear-gradient(135deg, #DC2626, #B91C1C) !important; }
.tawk-send-button { background: #3B82F6 !important; }
.tawk-send-button:hover { background: #2563EB !important; }
[data-theme="dark"] .tawk-button-circle {
    box-shadow: 0 4px 20px rgba(59, 130, 246, 0.6) !important;
}

/* ═══════════════════════════════════════════════
   TICKER
═══════════════════════════════════════════════ */
.sipda-ticker {
    background: var(--ticker-bg);
    border-bottom: 2px solid rgba(220,38,38,.4);
    transition: background .3s ease;
}
.ticker-badge {
    background: var(--c-darurat);
    border-radius: 7px;
    padding: 3px 12px;
    font-family: var(--font-display);
    font-size: 10.5px;
    font-weight: 800;
    letter-spacing: 1.4px;
    color: #fff;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}
.ticker-dot {
    width: 6px; height: 6px;
    background: #fff;
    border-radius: 50%;
    animation: blink 1s ease-in-out infinite;
}
@keyframes blink { 0%,100%{opacity:1} 50%{opacity:.2} }
.ticker-track {
    overflow: hidden;
    flex: 1;
    -webkit-mask-image: linear-gradient(90deg, transparent, black 5%, black 95%, transparent);
    mask-image: linear-gradient(90deg, transparent, black 5%, black 95%, transparent);
}
.ticker-inner {
    display: flex;
    gap: 3rem;
    white-space: nowrap;
    animation: tickerScroll 24s linear infinite;
    width: max-content;
}
@keyframes tickerScroll { 0%{transform:translateX(0)} 100%{transform:translateX(-50%)} }
.ticker-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 14px;
    border-radius: 8px;
    background: rgba(255,255,255,.08);
    color: rgba(255,255,255,.7);
    font-size: 12px;
    font-weight: 600;
    font-family: var(--font-display);
    text-decoration: none;
    white-space: nowrap;
    transition: background .15s;
    flex-shrink: 0;
}
.ticker-link:hover { background: rgba(255,255,255,.15); color: #fff; }

/* ═══════════════════════════════════════════════
   HERO
═══════════════════════════════════════════════ */
.sipda-hero {
    position: relative;
    background: var(--c-bg);
    overflow: hidden;
    padding: 72px 0 60px;
    transition: background .3s ease;
}
.hero-grid-bg {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, var(--c-border) 1px, transparent 1px);
    background-size: 28px 28px;
    opacity: .5;
    pointer-events: none;
    transition: opacity .3s ease;
}
[data-theme="dark"] .hero-grid-bg { opacity: .12; }
.hero-grid-bg::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 70% 60% at 50% 50%, transparent 30%, var(--c-bg) 100%);
}
.radar-wrap {
    position: absolute;
    right: -60px;
    top: 50%;
    transform: translateY(-50%);
    width: 560px;
    height: 560px;
    pointer-events: none;
}
.radar-ring {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 1.5px solid;
    animation: radarExpand 3.6s ease-out infinite;
    opacity: 0;
}
.radar-ring:nth-child(1) { animation-delay: 0s; border-color: rgba(220,38,38,.5); }
.radar-ring:nth-child(2) { animation-delay: 1.2s; border-color: rgba(220,38,38,.3); }
.radar-ring:nth-child(3) { animation-delay: 2.4s; border-color: rgba(220,38,38,.15); }
@keyframes radarExpand {
    0%   { transform: scale(.08); opacity: .9; }
    100% { transform: scale(1);   opacity: 0; }
}
.radar-sweep {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    background: conic-gradient(rgba(220,38,38,0) 270deg, rgba(220,38,38,.14) 315deg, rgba(220,38,38,.04) 360deg);
    animation: radarSweep 4s linear infinite;
}
@keyframes radarSweep { to { transform: rotate(360deg); } }
.radar-circles {
    position: absolute;
    inset: 0;
    border-radius: 50%;
    border: 1px solid rgba(220,38,38,.09);
    box-shadow: inset 0 0 0 calc(280px * .33) rgba(220,38,38,.035),
                inset 0 0 0 calc(280px * .66) rgba(220,38,38,.035);
}
.radar-crosshair {
    position: absolute;
    inset: 0;
}
.radar-crosshair::before {
    content: ''; position: absolute;
    top: 50%; left: 0; right: 0;
    height: 1px; background: rgba(220,38,38,.09);
}
.radar-crosshair::after {
    content: ''; position: absolute;
    left: 50%; top: 0; bottom: 0;
    width: 1px; background: rgba(220,38,38,.09);
}
.radar-center {
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    width: 14px; height: 14px;
    background: var(--c-darurat);
    border-radius: 50%;
    box-shadow: 0 0 0 5px rgba(220,38,38,.15), 0 0 24px rgba(220,38,38,.5);
    animation: centerPulse 2s ease-in-out infinite;
}
@keyframes centerPulse {
    0%,100% { box-shadow: 0 0 0 5px rgba(220,38,38,.15), 0 0 24px rgba(220,38,38,.5); }
    50%      { box-shadow: 0 0 0 10px rgba(220,38,38,.06), 0 0 40px rgba(220,38,38,.7); }
}
.live-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: 100px;
    padding: 5px 14px;
    font-size: 12px;
    font-weight: 700;
    color: var(--c-text);
    font-family: var(--font-display);
    margin-bottom: 20px;
    box-shadow: var(--shadow-sm);
    transition: background .3s ease, border-color .3s ease;
}
.live-dot {
    width: 8px; height: 8px;
    background: var(--c-aman);
    border-radius: 50%;
    box-shadow: 0 0 0 3px rgba(16,185,129,.2);
    animation: livePulse 2s ease-in-out infinite;
}
@keyframes livePulse {
    0%,100% { box-shadow: 0 0 0 3px rgba(16,185,129,.2); }
    50%      { box-shadow: 0 0 0 6px rgba(16,185,129,.06); }
}
.hero-headline {
    font-family: var(--font-display);
    font-size: clamp(2.1rem, 4.2vw, 3.2rem);
    font-weight: 800;
    line-height: 1.1;
    letter-spacing: -.5px;
    color: var(--c-text);
    margin-bottom: 16px;
}
.hero-headline .accent-line {
    display: block;
    background: linear-gradient(135deg, #DC2626 0%, #f97316 50%, #eab308 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-sub {
    font-size: 1rem;
    color: var(--c-muted);
    max-width: 440px;
    line-height: 1.7;
    margin-bottom: 28px;
}
.btn-sipda-primary {
    background: var(--c-darurat);
    border: none;
    color: #fff;
    font-family: var(--font-display);
    font-weight: 700;
    padding: 13px 26px;
    border-radius: var(--r-btn);
    font-size: 14.5px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: transform .18s, box-shadow .18s, background .18s;
    box-shadow: 0 4px 16px rgba(220,38,38,.35);
    text-decoration: none;
    cursor: pointer;
}
.btn-sipda-primary:hover {
    background: #b91c1c;
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(220,38,38,.45);
    color: #fff;
}
.btn-sipda-primary:active { transform: scale(.97); }
.btn-sipda-ghost {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    color: var(--c-text);
    font-family: var(--font-display);
    font-weight: 600;
    padding: 12px 24px;
    border-radius: var(--r-btn);
    font-size: 14.5px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: transform .18s, border-color .18s, box-shadow .18s;
    text-decoration: none;
    cursor: pointer;
    box-shadow: var(--shadow-sm);
}
.btn-sipda-ghost:hover {
    border-color: var(--c-border2);
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
    color: var(--c-text);
}
.hero-stats {
    display: grid;
    grid-template-columns: repeat(2,1fr);
    gap: 12px;
}
.stat-glass {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    padding: 18px 20px;
    position: relative;
    overflow: hidden;
    transition: transform .22s, box-shadow .22s, background .3s ease, border-color .3s ease;
    box-shadow: var(--shadow-sm);
    cursor: default;
}
.stat-glass::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--r-card) var(--r-card) 0 0;
}
.stat-glass.sg-darurat::before { background: var(--c-darurat); }
.stat-glass.sg-siaga::before   { background: var(--c-siaga); }
.stat-glass.sg-success::before { background: var(--c-aman); }
.stat-glass.sg-waspada::before { background: var(--c-waspada); }
.stat-glass:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}
.stat-label {
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: .8px;
    text-transform: uppercase;
    color: var(--c-muted2);
    margin-bottom: 8px;
}
.stat-value {
    font-family: var(--font-display);
    font-size: 2.6rem;
    font-weight: 800;
    line-height: 1;
    letter-spacing: -1px;
    font-variant-numeric: tabular-nums;
}
.stat-value.sv-darurat { color: var(--c-darurat); }
.stat-value.sv-siaga   { color: var(--c-siaga); }
.stat-value.sv-success { color: var(--c-aman); }
.stat-value.sv-waspada { color: var(--c-waspada); }
.stat-desc { font-size: 12px; color: var(--c-muted2); margin-top: 4px; }

/* ═══════════════════════════════════════════════
   GUEST CALL CENTER CARD
═══════════════════════════════════════════════ */
.guest-hero-card {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    padding: 28px 24px;
    box-shadow: var(--shadow-sm);
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: transform .22s, box-shadow .22s, background .3s ease, border-color .3s ease;
    cursor: pointer;
}
.guest-hero-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}
.guest-hero-card .ghc-icon {
    width: 56px;
    height: 56px;
    background: #FEE2E2;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}
[data-theme="dark"] .guest-hero-card .ghc-icon {
    background: rgba(220,38,38,.2);
}
.guest-hero-card .ghc-icon .material-symbols-outlined {
    color: #DC2626;
}
[data-theme="dark"] .guest-hero-card .ghc-icon .material-symbols-outlined {
    color: #F87171;
}
.guest-hero-card .ghc-title {
    font-family: var(--font-display);
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--c-text);
    margin-bottom: 4px;
}
.guest-hero-card .ghc-sub {
    font-size: 14px;
    color: var(--c-muted);
    margin-bottom: 12px;
    line-height: 1.6;
}
.emergency-contacts {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    margin-bottom: 14px;
}
.emergency-contact-item {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--c-bg);
    border: 1px solid var(--c-border);
    border-radius: 10px;
    padding: 8px 12px;
    transition: all .2s ease;
    cursor: pointer;
    text-decoration: none;
    color: var(--c-text);
}
.emergency-contact-item:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-sm);
    border-color: var(--c-border2);
}
.emergency-contact-item .ec-icon {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.emergency-contact-item .ec-icon.ec-red { background: #FEE2E2; color: #DC2626; }
.emergency-contact-item .ec-icon.ec-blue { background: #DBEAFE; color: #3B82F6; }
.emergency-contact-item .ec-icon.ec-green { background: #D1FAE5; color: #10B981; }
.emergency-contact-item .ec-icon.ec-yellow { background: #FEF3C7; color: #F59E0B; }
[data-theme="dark"] .emergency-contact-item .ec-icon.ec-red { background: rgba(220,38,38,.2); color: #F87171; }
[data-theme="dark"] .emergency-contact-item .ec-icon.ec-blue { background: rgba(59,130,246,.2); color: #60A5FA; }
[data-theme="dark"] .emergency-contact-item .ec-icon.ec-green { background: rgba(16,185,129,.2); color: #34D399; }
[data-theme="dark"] .emergency-contact-item .ec-icon.ec-yellow { background: rgba(245,158,11,.2); color: #FBBF24; }
.emergency-contact-item .ec-info {
    flex: 1;
    min-width: 0;
}
.emergency-contact-item .ec-name {
    font-size: 11px;
    font-weight: 600;
    color: var(--c-muted);
    font-family: var(--font-display);
    letter-spacing: .3px;
}
.emergency-contact-item .ec-number {
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 700;
    color: var(--c-text);
}
.guest-hero-card .ghc-divider {
    border: none;
    border-top: 1.5px solid var(--c-border);
    margin: 12px 0;
}
.guest-hero-card .ghc-features {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.guest-hero-card .ghc-features .gf-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: var(--c-muted);
}
.guest-hero-card .ghc-features .gf-item .material-symbols-outlined {
    font-size: 16px;
    color: var(--c-aman);
    font-variation-settings: 'FILL' 1;
}
.guest-hero-card .ghc-btn {
    margin-top: 16px;
    background: var(--c-darurat);
    color: #fff;
    border: none;
    padding: 12px 24px;
    border-radius: var(--r-btn);
    font-family: var(--font-display);
    font-weight: 700;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    transition: all .18s;
    box-shadow: 0 4px 16px rgba(220,38,38,.3);
    width: fit-content;
}
.guest-hero-card .ghc-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(220,38,38,.4);
    background: #b91c1c;
}
.guest-hero-card .ghc-btn:active { transform: scale(.97); }

/* ═══════════════════════════════════════════════
   SECTIONS SHARED
═══════════════════════════════════════════════ */
.sipda-section    { padding: 64px 0; transition: background .3s ease; }
.sipda-section-alt {
    background: var(--c-surface);
    border-top: 1.5px solid var(--c-border);
    border-bottom: 1.5px solid var(--c-border);
    transition: background .3s ease, border-color .3s ease;
}
.section-eyebrow {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: var(--c-darurat);
    margin-bottom: 8px;
    font-family: var(--font-display);
}
.section-title {
    font-family: var(--font-display);
    font-size: clamp(1.4rem, 2.6vw, 1.9rem);
    font-weight: 800;
    color: var(--c-text);
    margin-bottom: 4px;
    letter-spacing: -.3px;
    line-height: 1.2;
}
.section-sub { color: var(--c-muted); font-size: .91rem; line-height: 1.65; }
.section-divider {
    width: 32px; height: 3px;
    background: var(--c-darurat);
    border-radius: 10px;
    margin: 12px 0 0;
}
.section-divider.sd-blue { background: var(--c-siaga); }
.reveal {
    opacity: 0;
    transform: translateY(22px);
    transition: opacity .58s cubic-bezier(.16,1,.3,1), transform .58s cubic-bezier(.16,1,.3,1);
}
.reveal.visible { opacity: 1; transform: translateY(0); }
.btn-outline-sipda {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    border-radius: 11px;
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 600;
    border: 1.5px solid var(--c-border);
    color: var(--c-muted);
    background: var(--c-surface);
    text-decoration: none;
    transition: border-color .18s, color .18s, box-shadow .18s, transform .18s, background .3s ease;
    box-shadow: var(--shadow-sm);
    cursor: pointer;
}
.btn-outline-sipda:hover {
    border-color: var(--c-border2);
    color: var(--c-text);
    box-shadow: var(--shadow-md);
    transform: translateY(-1px);
}

/* ═══════════════════════════════════════════════
   WEATHER
═══════════════════════════════════════════════ */
.weather-section {
    background: var(--c-bg2);
    padding: 56px 0;
    border-top: 1.5px solid var(--c-border);
    border-bottom: 1.5px solid var(--c-border);
    transition: background .3s ease, border-color .3s ease;
}
.weather-strip {
    display: flex;
    gap: 10px;
    overflow-x: auto;
    padding-bottom: 4px;
    scrollbar-width: none;
    scroll-behavior: smooth;
}
.weather-strip::-webkit-scrollbar { display: none; }
.wcard {
    flex: 0 0 auto;
    width: 178px;
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    padding: 16px;
    cursor: pointer;
    transition: transform .22s, box-shadow .22s, border-color .22s, background .3s ease;
    box-shadow: var(--shadow-sm);
    position: relative;
    overflow: hidden;
}
.wcard::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 0 0 var(--r-card) var(--r-card);
    opacity: 0;
    transition: opacity .22s;
}
.wcard:hover { transform: translateY(-5px); box-shadow: var(--shadow-lg); border-color: var(--c-border2); }
.wcard:hover::after { opacity: 1; }
.wcard.wcard-active { border-color: var(--c-siaga); box-shadow: 0 0 0 3px rgba(59,130,246,.12), var(--shadow-md); }
.wcard.wcard-active::after { opacity: 1; }
.wcard.wt-sunny::after   { background: #FBBF24; }
.wcard.wt-cloudy::after  { background: #94A3B8; }
.wcard.wt-rain::after    { background: #60A5FA; }
.wcard.wt-storm::after   { background: #A78BFA; }
.wcard.wt-fog::after     { background: #94A3B8; }
.wcard.wt-night::after   { background: #818CF8; }
.wcard-city  { font-family: var(--font-display); font-size: 13px; font-weight: 700; color: var(--c-text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-bottom: 2px; }
.wcard-desc  { font-size: 11px; color: var(--c-muted2); text-transform: capitalize; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.wcard-icon-wrap {
    width: 46px; height: 46px;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 11px 0 9px;
}
.wcard.wt-sunny  .wcard-icon-wrap { background: rgba(251,191,36,.12); }
.wcard.wt-cloudy .wcard-icon-wrap { background: rgba(148,163,184,.1); }
.wcard.wt-rain   .wcard-icon-wrap { background: rgba(59,130,246,.1); }
.wcard.wt-storm  .wcard-icon-wrap { background: rgba(139,92,246,.12); }
.wcard.wt-fog    .wcard-icon-wrap { background: rgba(148,163,184,.08); }
.wcard.wt-night  .wcard-icon-wrap { background: rgba(99,102,241,.1); }
[data-theme="dark"] .wcard.wt-sunny  .wcard-icon-wrap { background: rgba(251,191,36,.2); }
[data-theme="dark"] .wcard.wt-cloudy .wcard-icon-wrap { background: rgba(148,163,184,.2); }
[data-theme="dark"] .wcard.wt-rain   .wcard-icon-wrap { background: rgba(59,130,246,.2); }
[data-theme="dark"] .wcard.wt-storm  .wcard-icon-wrap { background: rgba(139,92,246,.2); }
[data-theme="dark"] .wcard.wt-fog    .wcard-icon-wrap { background: rgba(148,163,184,.2); }
[data-theme="dark"] .wcard.wt-night  .wcard-icon-wrap { background: rgba(99,102,241,.2); }
.wcard-icon-symbol { font-size: 22px; font-variation-settings: 'FILL' 1; }
.wcard.wt-sunny  .wcard-icon-symbol { color: #FBBF24; }
.wcard.wt-cloudy .wcard-icon-symbol { color: #94A3B8; }
.wcard.wt-rain   .wcard-icon-symbol { color: #60A5FA; }
.wcard.wt-storm  .wcard-icon-symbol { color: #A78BFA; }
.wcard.wt-fog    .wcard-icon-symbol { color: #94A3B8; }
.wcard.wt-night  .wcard-icon-symbol { color: #818CF8; }
.wcard-temp {
    font-family: var(--font-display);
    font-size: 2rem;
    font-weight: 800;
    line-height: 1;
    color: var(--c-text);
    font-variant-numeric: tabular-nums;
    display: flex;
    align-items: flex-start;
    gap: 2px;
    margin-bottom: 10px;
}
.wcard-temp sup { font-size: .55em; font-weight: 600; color: var(--c-muted2); margin-top: 5px; }
.wcard-meta {
    display: flex;
    flex-direction: column;
    gap: 4px;
    border-top: 1px solid var(--c-border);
    padding-top: 9px;
}
.wcard-meta-row { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--c-muted); }
.wcard-meta-row .material-symbols-outlined { font-size: 13px; font-variation-settings: 'FILL' 1; }
.wcard-humidity-bar { height: 3px; background: var(--c-bg); border-radius: 10px; margin-top: 5px; overflow: hidden; }
.wcard-humidity-fill { height: 100%; border-radius: 10px; background: linear-gradient(90deg,#3B82F6,#06b6d4); transition: width .6s cubic-bezier(.16,1,.3,1); }
.weather-scroll-btn {
    width: 34px; height: 34px;
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all .16s, background .3s ease;
    color: var(--c-muted);
    box-shadow: var(--shadow-sm);
}
.weather-scroll-btn:hover { border-color: var(--c-border2); color: var(--c-text); box-shadow: var(--shadow-md); }
.weather-scroll-btn.disabled { opacity: .3; pointer-events: none; }
.weather-noapi {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    padding: 18px 22px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: var(--shadow-sm);
    transition: background .3s ease, border-color .3s ease;
}
.weather-noapi-icon {
    width: 44px; height: 44px;
    background: #eff6ff;
    border: 1.5px solid #bfdbfe;
    border-radius: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
[data-theme="dark"] .weather-noapi-icon {
    background: rgba(59,130,246,.15);
    border-color: rgba(59,130,246,.3);
}

/* ═══════════════════════════════════════════════
   MAP CARD
═══════════════════════════════════════════════ */
.map-card {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    overflow: hidden;
    height: 100%;
    box-shadow: var(--shadow-sm);
    transition: background .3s ease, border-color .3s ease;
}
.map-card-header {
    padding: 13px 17px;
    border-bottom: 1px solid var(--c-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 8px;
    background: var(--c-surface);
    transition: background .3s ease, border-color .3s ease;
}
.map-card-title {
    font-family: var(--font-display);
    font-size: 13px;
    font-weight: 700;
    color: var(--c-text);
    display: flex;
    align-items: center;
    gap: 7px;
}
.map-search-wrapper {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--c-bg);
    border: 1px solid var(--c-border);
    border-radius: 10px;
    padding: 4px 10px;
    transition: border-color .2s;
    flex: 1;
    max-width: 260px;
}
.map-search-wrapper:focus-within {
    border-color: var(--c-siaga);
    box-shadow: 0 0 0 3px rgba(59,130,246,.1);
}
.map-search-wrapper .material-symbols-outlined {
    font-size: 16px;
    color: var(--c-muted2);
}
.map-search-wrapper input {
    border: none;
    background: transparent;
    padding: 6px 4px;
    font-size: 12px;
    color: var(--c-text);
    width: 100%;
    outline: none;
    font-family: var(--font-body);
}
.map-search-wrapper input::placeholder {
    color: var(--c-muted2);
}
.map-search-wrapper input:focus {
    outline: none;
}
.map-search-btn {
    background: var(--c-siaga);
    border: none;
    color: #fff;
    padding: 4px 12px;
    border-radius: 7px;
    font-size: 11px;
    font-weight: 600;
    cursor: pointer;
    font-family: var(--font-display);
    transition: background .2s;
    white-space: nowrap;
}
.map-search-btn:hover {
    background: #2563EB;
}
.map-card-link {
    font-size: 12px;
    color: var(--c-muted);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: color .15s;
    white-space: nowrap;
}
.map-card-link:hover { color: var(--c-siaga); }
.leaflet-tile-pane { filter: var(--map-filter); }
.leaflet-control-zoom {
    border: 1px solid var(--c-border) !important;
    border-radius: 10px !important;
    overflow: hidden;
}
.leaflet-control-zoom a {
    background: var(--c-surface) !important;
    color: var(--c-text) !important;
    border-color: var(--c-border) !important;
}
.leaflet-control-zoom a:hover {
    background: var(--c-bg) !important;
}
.leaflet-popup-content-wrapper {
    background: var(--c-surface) !important;
    color: var(--c-text) !important;
    border-radius: 12px !important;
    border: 1px solid var(--c-border) !important;
    box-shadow: var(--shadow-lg) !important;
}
.leaflet-popup-tip {
    background: var(--c-surface) !important;
}
.leaflet-popup-close-button {
    color: var(--c-muted) !important;
}

/* ═══════════════════════════════════════════════
   BENCANA CARDS
═══════════════════════════════════════════════ */
.bcard {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    padding: 18px;
    transition: transform .22s, box-shadow .22s, background .3s ease, border-color .3s ease;
    position: relative;
    overflow: hidden;
    height: 100%;
    cursor: pointer;
    box-shadow: var(--shadow-sm);
}
.bcard::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--r-card) var(--r-card) 0 0;
}
.bcard.bc-darurat::before { background: var(--c-darurat); }
.bcard.bc-siaga::before   { background: var(--c-siaga); }
.bcard.bc-waspada::before { background: var(--c-waspada); }
.bcard:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl);
}
.bcard-expand {
    max-height: 0;
    overflow: hidden;
    transition: max-height .38s cubic-bezier(.16,1,.3,1), opacity .3s;
    opacity: 0;
}
.bcard.open .bcard-expand { max-height: 240px; opacity: 1; }
.bcard-expand-inner {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid var(--c-border);
}
.expand-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 6px; }
.expand-key { color: var(--c-muted2); font-weight: 600; }
.expand-val { font-weight: 700; color: var(--c-text); }
.impact-bar { height: 6px; background: var(--c-bg); border: 1px solid var(--c-border); border-radius: 10px; overflow: hidden; margin-top: 4px; }
.impact-fill { height: 100%; border-radius: 10px; transition: width .7s cubic-bezier(.16,1,.3,1); }
.bcard-icon { width: 38px; height: 38px; border-radius: 11px; display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0; }
.bcard-title { font-family: var(--font-display); font-weight: 700; color: var(--c-text); font-size: .93rem; line-height: 1.3; }
.bcard-badge {
    display: inline-block;
    font-size: 10px; font-weight: 700; letter-spacing: .5px;
    border-radius: 100px; padding: 2px 9px;
    font-family: var(--font-display);
}
.bcard-badge.bd { background: #FEE2E2; color: #b91c1c; border: 1px solid #FCA5A5; }
.bcard-badge.bs { background: #DBEAFE; color: #1d4ed8; border: 1px solid #BFDBFE; }
.bcard-badge.bw { background: #FEF3C7; color: #92400e; border: 1px solid #FDE68A; }
[data-theme="dark"] .bcard-badge.bd { background: rgba(220,38,38,.2); color: #F87171; border-color: rgba(220,38,38,.3); }
[data-theme="dark"] .bcard-badge.bs { background: rgba(59,130,246,.2); color: #60A5FA; border-color: rgba(59,130,246,.3); }
[data-theme="dark"] .bcard-badge.bw { background: rgba(245,158,11,.2); color: #FBBF24; border-color: rgba(245,158,11,.3); }
.bcard-loc { font-size: 12px; color: var(--c-muted); display: flex; align-items: flex-start; gap: 4px; margin: 9px 0 5px; }
.bcard-desc { font-size: 12px; color: var(--c-muted); line-height: 1.55; }
.bcard-link {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    margin-top: 12px; padding: 5px 13px; border-radius: 9px;
    transition: filter .15s; font-family: var(--font-display);
}
.bcard-link.bd { color: var(--c-darurat); background: #FEE2E2; }
.bcard-link.bs { color: #1d4ed8;          background: #DBEAFE; }
.bcard-link.bw { color: #92400e;          background: #FEF3C7; }
[data-theme="dark"] .bcard-link.bd { background: rgba(220,38,38,.15); }
[data-theme="dark"] .bcard-link.bs { background: rgba(59,130,246,.15); }
[data-theme="dark"] .bcard-link.bw { background: rgba(245,158,11,.15); }
.bcard-link:hover { filter: brightness(1.1); }
.bcard-expand-btn {
    font-size: 11px; font-weight: 600;
    color: var(--c-muted); background: var(--c-bg);
    border: 1.5px solid var(--c-border);
    border-radius: 8px; padding: 4px 10px;
    cursor: pointer; transition: all .16s;
    font-family: var(--font-display);
    display: inline-flex; align-items: center; gap: 4px;
    margin-top: 12px; margin-left: 6px;
}
.bcard-expand-btn:hover { border-color: var(--c-border2); color: var(--c-text); }
.bcard-expand-icon { transition: transform .3s; font-size: 14px; }
.bcard.open .bcard-expand-icon { transform: rotate(180deg); }

.empty-card {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    padding: 3rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: background .3s ease, border-color .3s ease;
}
.empty-icon {
    width: 68px; height: 68px;
    background: #F0FDF4;
    border: 1.5px solid #BBF7D0;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}
[data-theme="dark"] .empty-icon {
    background: rgba(16,185,129,.15);
    border-color: rgba(16,185,129,.3);
}

/* ═══════════════════════════════════════════════
   BERITA CARDS
═══════════════════════════════════════════════ */
.berita-featured-card {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    overflow: hidden;
    height: 100%;
    transition: transform .22s, box-shadow .22s, background .3s ease, border-color .3s ease;
    box-shadow: var(--shadow-sm);
    cursor: pointer;
    text-decoration: none;
    display: block;
}
.berita-featured-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-xl); }
.berita-featured-img {
    height: 240px;
    object-fit: cover;
    width: 100%;
    background: var(--c-bg);
}
.berita-placeholder-img {
    height: 240px;
    background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
    display: flex;
    align-items: center;
    justify-content: center;
}
.berita-featured-body { padding: 20px 22px; }
.berita-tag {
    display: inline-block;
    background: #DBEAFE; color: #1d4ed8;
    border: 1px solid #BFDBFE;
    border-radius: 100px; padding: 3px 11px;
    font-size: 11px; font-weight: 700; letter-spacing: .5px;
    font-family: var(--font-display);
}
[data-theme="dark"] .berita-tag {
    background: rgba(59,130,246,.2);
    color: #60A5FA;
    border-color: rgba(59,130,246,.3);
}
.berita-date { font-size: 12px; color: var(--c-muted2); display: flex; align-items: center; gap: 4px; }
.berita-featured-title { font-family: var(--font-display); font-size: 1.12rem; font-weight: 700; color: var(--c-text); line-height: 1.35; margin: 10px 0 9px; }
.berita-featured-excerpt { font-size: 13px; color: var(--c-muted); line-height: 1.65; margin-bottom: 18px; }
.berita-sm-card {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    overflow: hidden;
    transition: border-color .2s, transform .2s, box-shadow .2s, background .3s ease;
    box-shadow: var(--shadow-sm);
    cursor: pointer;
    text-decoration: none;
    display: block;
}
.berita-sm-card:hover { border-color: var(--c-border2); transform: translateX(4px); box-shadow: var(--shadow-md); }
.berita-sm-img { width: 100%; height: 100%; object-fit: cover; min-height: 110px; background: var(--c-bg); }
.berita-sm-placeholder {
    background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
    min-height: 110px; display: flex; align-items: center; justify-content: center;
}
.berita-sm-body { padding: 13px 15px; }
.berita-sm-title { font-family: var(--font-display); font-size: 13px; font-weight: 700; color: var(--c-text); line-height: 1.35; margin-bottom: 11px; }
.berita-more-tile {
    background: var(--c-surface);
    border: 1.5px dashed var(--c-border);
    border-radius: var(--r-card);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    padding: 2rem; text-decoration: none;
    transition: border-color .2s, background .2s;
    gap: 6px;
    box-shadow: var(--shadow-sm);
    cursor: pointer;
}
.berita-more-tile:hover { border-color: #BFDBFE; background: #EFF6FF; }
[data-theme="dark"] .berita-more-tile:hover { border-color: rgba(59,130,246,.3); background: rgba(59,130,246,.08); }

/* ═══════════════════════════════════════════════
   FEATURES
═══════════════════════════════════════════════ */
.feature-card {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    padding: 26px;
    height: 100%;
    transition: transform .22s, box-shadow .22s, background .3s ease, border-color .3s ease;
    position: relative;
    overflow: hidden;
    cursor: default;
    box-shadow: var(--shadow-sm);
}
.feature-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 0 0 var(--r-card) var(--r-card);
    opacity: 0;
    transition: opacity .22s;
}
.feature-card.fc-1::after { background: #8B5CF6; }
.feature-card.fc-2::after { background: var(--c-siaga); }
.feature-card.fc-3::after { background: var(--c-aman); }
.feature-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-xl); }
.feature-card:hover::after { opacity: 1; }
.feature-icon { width: 52px; height: 52px; border-radius: 15px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 18px; }
.fi-1 { background: #F5F3FF; }
.fi-2 { background: #EFF6FF; }
.fi-3 { background: #ECFDF5; }
[data-theme="dark"] .fi-1 { background: rgba(139,92,246,.15); }
[data-theme="dark"] .fi-2 { background: rgba(59,130,246,.15); }
[data-theme="dark"] .fi-3 { background: rgba(16,185,129,.15); }
.feature-number { position: absolute; top: 18px; right: 20px; font-family: var(--font-display); font-size: 3.5rem; font-weight: 800; color: rgba(0,0,0,.04); line-height: 1; user-select: none; }
[data-theme="dark"] .feature-number { color: rgba(255,255,255,.04); }
.feature-title { font-family: var(--font-display); font-size: 1rem; font-weight: 700; color: var(--c-text); margin-bottom: 9px; }
.feature-desc  { font-size: 13.5px; color: var(--c-muted); line-height: 1.65; }

.cta-section {
    background: var(--cta-bg);
    position: relative;
    overflow: hidden;
    transition: background .3s ease;
}
.cta-benefit-item {
    display: flex; align-items: center; gap: 10px;
    font-size: 14px; color: rgba(255,255,255,.7);
}
.cta-benefit-icon {
    width: 28px; height: 28px;
    background: rgba(16,185,129,.15);
    border: 1px solid rgba(16,185,129,.25);
    border-radius: 8px;
    display: inline-flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.cta-rings { position: absolute; right: 8%; top: 50%; transform: translateY(-50%); width: 300px; height: 300px; pointer-events: none; }
.cta-ring {
    position: absolute; border-radius: 50%;
    border: 1px solid rgba(16,185,129,.12);
}
.mag-card { transition: transform .15s cubic-bezier(.16,1,.3,1); }
.guest-bencana-placeholder {
    background: var(--c-surface);
    border: 1.5px solid var(--c-border);
    border-radius: var(--r-card);
    padding: 32px 24px;
    text-align: center;
    box-shadow: var(--shadow-sm);
    transition: background .3s ease, border-color .3s ease;
}
.guest-bencana-placeholder .gbp-icon {
    width: 64px;
    height: 64px;
    background: #EFF6FF;
    border: 1.5px solid #BFDBFE;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 12px;
}
[data-theme="dark"] .guest-bencana-placeholder .gbp-icon {
    background: rgba(59,130,246,.15);
    border-color: rgba(59,130,246,.3);
}
.guest-bencana-placeholder .gbp-title {
    font-family: var(--font-display);
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--c-text);
    margin-bottom: 4px;
}
.guest-bencana-placeholder .gbp-desc {
    font-size: 14px;
    color: var(--c-muted);
    max-width: 360px;
    margin: 0 auto 12px;
    line-height: 1.6;
}
.guest-bencana-placeholder .gbp-number {
    font-family: var(--font-display);
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--c-darurat);
}
.guest-bencana-placeholder .gbp-number small {
    font-size: 14px;
    font-weight: 600;
    color: var(--c-muted);
}

/* ═══════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════ */
@media (max-width: 768px) {
    .guest-hero-card .ghc-number { font-size: 1.6rem; }
    .emergency-contacts {
        grid-template-columns: 1fr;
    }
    .map-search-wrapper {
        max-width: 100%;
    }
    .map-card-header {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
@endpush

{{-- ══════════════════════════════════════════
     CONTENT — SAMA SEPERTI SEBELUMNYA
     (ticker, hero, weather, map, berita, fitur, cta)
══════════════════════════════════════════ --}}
{{-- ... (sama seperti sebelumnya, tidak diubah) --}}

{{-- DARK MODE TOGGLE --}}
<button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
    <span class="material-symbols-outlined" id="themeIcon">dark_mode</span>
</button>

@endsection

@push('scripts')
{{-- ══════════════════════════════════════════
     DARK MODE TOGGLE — DIPERBAIKI
══════════════════════════════════════════ --}}
<script>
(function() {
    const toggle = document.getElementById('themeToggle');
    const icon = document.getElementById('themeIcon');
    const html = document.documentElement;
    
    // Cek saved theme
    const savedTheme = localStorage.getItem('sipda-theme');
    if (savedTheme === 'dark') {
        html.setAttribute('data-theme', 'dark');
        icon.textContent = 'light_mode';
    } else {
        html.removeAttribute('data-theme');
        icon.textContent = 'dark_mode';
    }
    
    toggle.addEventListener('click', function(e) {
        e.preventDefault();
        const isDark = html.getAttribute('data-theme') === 'dark';
        
        if (isDark) {
            html.removeAttribute('data-theme');
            icon.textContent = 'dark_mode';
            localStorage.setItem('sipda-theme', 'light');
            console.log('✅ Switched to LIGHT mode');
        } else {
            html.setAttribute('data-theme', 'dark');
            icon.textContent = 'light_mode';
            localStorage.setItem('sipda-theme', 'dark');
            console.log('✅ Switched to DARK mode');
        }
        
        // Update map
        if (window.updateMapTheme) {
            window.updateMapTheme();
        }
    });
    
    console.log('✅ Dark mode toggle initialized');
})();
</script>

{{-- ══════════════════════════════════════════
     LEAFLET MAP — Dengan fix error 403
══════════════════════════════════════════ --}}
<script>
(function() {
    const mapEl = document.getElementById('homeMap');
    if (!mapEl) return;

    // ── Tempat Populer ──
    const popularPlaces = [
        { name: 'Alun-alun Bandung', lat: -6.9217, lng: 107.6071, category: 'Landmark', icon: '🏛️' },
        { name: 'Gedung Sate', lat: -6.9025, lng: 107.6186, category: 'Landmark', icon: '🏛️' },
        { name: 'Trans Studio Mall', lat: -6.9121, lng: 107.6136, category: 'Mall', icon: '🛍️' },
        { name: 'Dago Atas', lat: -6.8855, lng: 107.6143, category: 'Wisata', icon: '🏔️' },
        { name: 'Kebun Binatang Bandung', lat: -6.9083, lng: 107.6185, category: 'Wisata', icon: '🐘' },
        { name: 'Museum Geologi', lat: -6.9031, lng: 107.6222, category: 'Museum', icon: '🏺' },
        { name: 'Taman Hutan Raya', lat: -6.8781, lng: 107.6285, category: 'Wisata', icon: '🌳' },
        { name: 'Stasiun Bandung', lat: -6.9149, lng: 107.6016, category: 'Transportasi', icon: '🚉' },
        { name: 'Braga Street', lat: -6.9167, lng: 107.6087, category: 'Kuliner', icon: '🍽️' },
        { name: 'PVJ Mall', lat: -6.8942, lng: 107.6121, category: 'Mall', icon: '🛍️' },
    ];

    const bencanaData = @json($bencanaAktif);
    const STATUS_COLORS = {
        Darurat: '#DC2626',
        Siaga: '#3B82F6',
        Waspada: '#F59E0B',
        Aman: '#10B981'
    };

    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    const tileUrl = isDark 
        ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
        : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';

    const tiles = L.tileLayer(tileUrl, {
        attribution: '&copy; OSM &copy; CARTO',
        maxZoom: 19
    });

    const homeMap = L.map('homeMap', {
        zoomControl: false,
        scrollWheelZoom: false,
        attributionControl: false
    }).setView([-6.9175, 107.6191], 13);

    tiles.addTo(homeMap);
    L.control.attribution({ prefix: false, position: 'bottomleft' })
        .addAttribution('&copy; OSM CARTO')
        .addTo(homeMap);
    L.control.zoom({ position: 'topright' }).addTo(homeMap);

    // ── Custom Icons ──
    function createPlaceIcon(icon, color = '#3B82F6') {
        return L.divIcon({
            className: '',
            html: `<div style="width:36px;height:36px;border-radius:50%;
                        background:${color};border:2.5px solid rgba(255,255,255,.9);
                        box-shadow:0 2px 12px ${color}55;
                        display:flex;align-items:center;justify-content:center;
                        font-size:16px;color:#fff;font-weight:700;">
                        ${icon}
                    </div>`,
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -20]
        });
    }

    function createDisasterIcon(color) {
        return L.divIcon({
            className: '',
            html: `<div style="position:relative;width:32px;height:32px;">
                       <div style="width:32px;height:32px;border-radius:50%;
                            background:${color};border:3px solid rgba(255,255,255,.9);
                            box-shadow:0 2px 16px ${color}66;
                            display:flex;align-items:center;justify-content:center;
                            font-size:14px;color:#fff;font-weight:700;">
                            ⚠
                       </div>
                   </div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16],
            popupAnchor: [0, -20]
        });
    }

    // ── Add Places ──
    const placeColors = {
        'Landmark': '#8B5CF6',
        'Mall': '#3B82F6',
        'Wisata': '#10B981',
        'Museum': '#F59E0B',
        'Transportasi': '#6B7280',
        'Kuliner': '#DC2626'
    };

    popularPlaces.forEach(place => {
        const color = placeColors[place.category] || '#3B82F6';
        const icon = createPlaceIcon(place.icon, color);
        const marker = L.marker([place.lat, place.lng], { icon }).addTo(homeMap);
        marker.bindPopup(`
            <div style="font-family:'Space Grotesk',sans-serif;padding:2px 0;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <span style="font-size:20px;">${place.icon}</span>
                    <strong style="font-size:15px;">${place.name}</strong>
                </div>
                <span style="background:${color}22;color:${color};border-radius:100px;padding:2px 12px;font-size:10px;font-weight:700;">${place.category}</span>
            </div>
        `, { maxWidth: 200 });
    });

    // ── Add Disaster Markers ──
    bencanaData.forEach(b => {
        if (!b.latitude || !b.longitude) return;
        const color = STATUS_COLORS[b.tingkat_status] || '#6b7280';
        const jenis = b.jenis?.nama_bencana ?? 'Bencana';
        const textClr = (b.tingkat_status === 'Waspada' || b.tingkat_status === 'Aman') ? '#1a1a1a' : '#fff';

        const icon = createDisasterIcon(color);
        const marker = L.marker([b.latitude, b.longitude], { icon }).addTo(homeMap);
        marker.bindPopup(`
            <div style="font-family:'Space Grotesk',sans-serif;padding:4px 0;min-width:160px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                    <span style="background:${color};color:${textClr};border-radius:100px;padding:2px 12px;font-size:10px;font-weight:700;">${b.tingkat_status}</span>
                    <span style="font-size:11px;color:#6B7280;">⚠ Bencana</span>
                </div>
                <strong style="font-size:14px;display:block;margin-bottom:2px;">${jenis}</strong>
                <span style="color:#6B7280;font-size:12px;display:flex;align-items:center;gap:4px;">
                    📍 ${b.lokasi}
                </span>
                <div style="margin-top:8px;display:flex;justify-content:flex-end;">
                    <a href="/bencana/${b.id}" style="background:${color};color:#fff;border-radius:8px;padding:4px 14px;font-size:11px;font-weight:600;text-decoration:none;">Detail →</a>
                </div>
            </div>
        `, { maxWidth: 220 });
    });

    // ── Fit bounds ──
    const allMarkers = [];
    homeMap.eachLayer(layer => {
        if (layer instanceof L.Marker) {
            allMarkers.push(layer);
        }
    });
    if (allMarkers.length > 0) {
        homeMap.fitBounds(L.featureGroup(allMarkers).getBounds().pad(0.15));
    }

    // ── Search ──
    const searchInput = document.getElementById('mapSearchInput');
    const searchBtn = document.getElementById('mapSearchBtn');

    function performSearch() {
        const query = searchInput.value.trim().toLowerCase();
        if (!query) {
            if (allMarkers.length > 0) {
                homeMap.fitBounds(L.featureGroup(allMarkers).getBounds().pad(0.15));
            }
            return;
        }

        let found = null;
        let foundLat = null;
        let foundLng = null;

        for (const place of popularPlaces) {
            if (place.name.toLowerCase().includes(query)) {
                found = place;
                foundLat = place.lat;
                foundLng = place.lng;
                break;
            }
        }

        if (!found) {
            for (const b of bencanaData) {
                if (b.lokasi && b.lokasi.toLowerCase().includes(query)) {
                    found = b.jenis?.nama_bencana || 'Bencana';
                    foundLat = b.latitude;
                    foundLng = b.longitude;
                    break;
                }
            }
        }

        if (found && foundLat && foundLng) {
            homeMap.setView([foundLat, foundLng], 15);
            homeMap.eachLayer(layer => {
                if (layer instanceof L.Marker) {
                    const latLng = layer.getLatLng();
                    const dist = Math.sqrt(
                        Math.pow(latLng.lat - foundLat, 2) + 
                        Math.pow(latLng.lng - foundLng, 2)
                    );
                    if (dist < 0.01) {
                        layer.openPopup();
                    }
                }
            });
        } else {
            alert('Tempat tidak ditemukan. Coba: Alun-alun, Gedung Sate, Braga, dll.');
        }
    }

    searchBtn.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') performSearch();
    });

    // ── Update map theme ──
    window.updateMapTheme = function() {
        const isDarkNow = document.documentElement.getAttribute('data-theme') === 'dark';
        const newUrl = isDarkNow 
            ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
            : 'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png';
        tiles.setUrl(newUrl);
    };

    const observer = new MutationObserver(window.updateMapTheme);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

})();
</script>
@endpush