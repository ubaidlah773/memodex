# MEMODEX — Design System & UX Specification

> **Dokumen acuan utama desain produk.** Semua halaman, komponen, dan interaksi MEMODEX harus mengikuti panduan ini. Bila ada keputusan visual yang belum dijelaskan, pilih solusi yang paling sederhana, konsisten, mudah digunakan, dan terasa dibuat dengan sengaja.

## 1. Product direction

**MEMODEX** adalah personal digital card collection: ruang untuk mengubah foto menjadi kartu koleksi, menyusun kartu ke dalam binder/album, dan menikmati kembali kenangan seperti membuka koleksi trading card.

MEMODEX mengambil inspirasi umum dari pengalaman Pokédex dan trading-card collector: katalog bernomor, pencarian cepat, koleksi, rarity, binder, dan card viewer. MEMODEX harus memiliki identitas visual orisinal sendiri. Jangan menyalin layout, logo, ikon, font, artwork, nama karakter, atau aset resmi Pokémon maupun game tertentu.

### Product principles

1. **Cards are the hero.** Kartu foto adalah konten utama; chrome aplikasi harus mendukung, bukan bersaing dengannya.
2. **Collector experience, not admin dashboard.** Pengalaman harus terasa seperti mengelola koleksi yang personal, bukan template SaaS dashboard generik.
3. **Quiet confidence.** Desain rapi, editorial, terukur, dan punya detail kecil. Hindari dekorasi berlebihan.
4. **Original photo first.** Foto asli tetap utuh; proses pembuatan kartu menambahkan frame, tipografi, atribut, dan efek tanpa mengubah identitas foto.
5. **Flexible, not rigid.** Layout terasa luwes dan responsif; jangan membuat semua elemen menjadi kotak-kotak kaku.
6. **Functional polish.** Animasi, hover, filter, editor, dan tombol harus memiliki fungsi nyata, bukan sekadar hiasan.

## 2. Art direction

### Visual keywords

- collectible archive
- modern Pokédex-inspired catalogue
- premium trading-card binder
- editorial photography
- subtle retro handheld details
- precise typography
- tactile but restrained

### Overall mood

Gunakan **dark collector interface** sebagai tema utama: deep ink/navy untuk lingkungan aplikasi, permukaan slate yang sedikit berbeda tingkat kecerahannya, tipografi terang, dan satu aksen biru yang digunakan secara hemat. Warna kartu berasal dari template/rarity masing-masing, sehingga grid koleksi terasa hidup tanpa membuat seluruh UI penuh warna.

Jangan membuat seluruh antarmuka hitam-biru neon. Jangan gunakan glow, gradient, blur, glassmorphism, dan outline menyala di semua komponen. Efek foil/holographic hanya diterapkan pada artwork kartu yang memang memiliki rarity/efek tersebut.

## 3. Design tokens

Gunakan CSS variables atau Tailwind theme tokens. Jangan menyebarkan kode warna acak di setiap komponen.

### Core palette

| Token | Hex | Penggunaan |
|---|---|---|
| `--color-bg` | `#0C1118` | Latar utama aplikasi |
| `--color-panel` | `#121B26` | Sidebar, panel utama, modal |
| `--color-panel-raised` | `#192535` | Hover surface, card container, dropdown |
| `--color-panel-soft` | `#202E40` | Subtle highlight / selected surface |
| `--color-border` | `#2A394B` | Border halus dan pemisah |
| `--color-text` | `#F1F4F8` | Teks utama |
| `--color-text-muted` | `#9BAABD` | Teks sekunder |
| `--color-text-subtle` | `#718196` | Metadata dan hint |
| `--color-primary` | `#83B9F2` | Aksi utama, link, focus |
| `--color-primary-strong` | `#5598DE` | Hover/active primary |
| `--color-accent-warm` | `#E6B979` | Detail koleksi, badge khusus secara hemat |
| `--color-success` | `#78C5A2` | Status berhasil |
| `--color-warning` | `#E7B96F` | Peringatan |
| `--color-danger` | `#E47D7D` | Aksi destruktif/error |

### Rarity colors

Rarity memiliki aksen masing-masing pada **kartu**, bukan pada keseluruhan halaman.

| Rarity | Accent direction | Catatan |
|---|---|---|
| BASIC | Warm neutral / brass | Bersih, sederhana |
| BASIC ★★ | Cool silver-blue | Sedikit lebih premium |
| BASIC ★★★ | Muted violet / prism | Detail holographic lebih kaya |
| EX | Electric blue / silver | Enerjik, tidak neon berlebihan |
| FULL ART | Rich artwork-led | Artwork mendominasi, frame minimal |
| RAINBOW | Iridescent spectrum | Efek hanya pada bidang foil tertentu |
| SECRET RARE | Muted gold | Premium, tidak memenuhi seluruh kartu dengan emas |

Jika template rarity atau artwork resmi dari pengguna tersedia di repository, gunakan aset dan aturan yang sudah ada. Jangan menggambar ulang, mengganti, atau mengubah template tersebut tanpa alasan teknis yang jelas.

## 4. Typography

- Gunakan font sans-serif modern yang mudah dibaca. Prioritaskan font yang sudah ada di project; jika belum ada, gunakan `Inter`, `Manrope`, atau system sans-serif stack.
- Angka koleksi, nomor kartu, HP, dan statistik harus menggunakan tabular numerals bila tersedia.
- Gunakan uppercase hanya untuk label pendek seperti `COLLECTION`, `CARD NO.`, atau metadata kecil. Hindari uppercase pada paragraf.
- Hindari terlalu banyak ukuran font. Buat hierarki konsisten: page title, section title, body, metadata.
- Jangan memakai font pixel/arcade pada seluruh aplikasi. Detail retro boleh dipakai sangat terbatas pada nomor/label kecil jika cocok.

## 5. Layout system

### Desktop

- Optimalkan untuk lebar 1280–1440 px dan tetap bekerja sampai 1920 px.
- Gunakan app shell dengan sidebar kiri sekitar 224–248 px dan main content yang fleksibel.
- Topbar ringkas: breadcrumb/title context, search atau quick action yang relevan, dan profile menu.
- Konten utama maksimal sekitar 1440 px; jangan membuat kolom terlalu lebar sehingga jarak baca berlebihan.
- Gunakan spacing rhythm berbasis 4/8 px.
- Grid kartu responsif, umumnya 5–7 kolom pada layar besar, bergantung lebar kartu dan panel filter.

### Tablet

- Sidebar dapat collapse menjadi rail atau drawer.
- Grid kartu 3–5 kolom.
- Toolbar filter harus bisa wrap dengan rapi; hindari horizontal overflow yang tidak disengaja.

### Mobile

- Gunakan topbar ringkas dan bottom navigation untuk area utama.
- Navigasi utama: Home, Pokédex, Create, Binder, Profile/More. Tombol Create boleh lebih menonjol, tetapi jangan dibuat seperti tombol iklan.
- Grid kartu 2 kolom pada layar kecil, 3 kolom pada layar yang lebih lebar.
- Filter dibuka melalui bottom sheet/drawer, bukan memaksakan sidebar desktop.
- Form editor disusun vertikal; preview kartu dapat sticky pada bagian atas atau ditempatkan pada panel terpisah yang mudah dijangkau.
- Target sentuh minimal sekitar 44 × 44 px.

### Breakpoints

Gunakan breakpoint Tailwind standar kecuali repository sudah memiliki sistem sendiri:

- `sm`: 640 px
- `md`: 768 px
- `lg`: 1024 px
- `xl`: 1280 px
- `2xl`: 1536 px

Jangan menulis breakpoint yang tidak dibutuhkan hanya untuk menyesuaikan satu screenshot.

## 6. Navigation & information architecture

### Main navigation

1. **Home** — ringkasan koleksi dan aktivitas terbaru.
2. **Pokédex** — seluruh kartu; pencarian, filter, sorting.
3. **Create** — upload foto dan pembuatan kartu.
4. **Binder** — album dan susunan koleksi.
5. **Favorites** — kartu yang ditandai.
6. **Profile / Settings** — akun, storage, privasi.

Menu admin hanya terlihat bagi role admin dan dipisahkan dari navigasi user biasa.

## 7. Page specifications

### 7.1 Home / Collection overview

**Tujuan:** memberi rasa memiliki koleksi, bukan menampilkan KPI bisnis.

Urutan konten:

1. Welcome line yang personal dan tidak berlebihan.
2. Featured card atau `Continue exploring` sebagai visual utama.
3. Ringkasan ringkas: total cards, albums, favorites, storage used.
4. Recently added cards.
5. Albums / binders yang terakhir dibuka.
6. Quick action `Create a card`.

Guidelines:
- Jangan gunakan 8–12 KPI cards seperti dashboard SaaS.
- Hindari grafik dekoratif yang tidak menjawab kebutuhan.
- Gunakan kartu koleksi asli milik user sebagai konten visual utama.
- Untuk akun baru, tampilkan onboarding empty state yang menarik dan mengarahkan ke upload foto.

### 7.2 Pokédex / All cards

**Tujuan:** menemukan dan menelusuri kartu dengan cepat.

Komponen:
- Page title `Pokédex` dan total jumlah kartu.
- Search berdasarkan nama, nomor kartu, skill, atau teks yang relevan.
- Filter: rarity, type, album, date created.
- Sort: newest, oldest, name, card number.
- Toggle grid/binder view bila masuk akal.
- Card grid dengan rasio portrait dan jarak antar kartu yang konsisten.
- Pagination atau load more yang sesuai dengan backend.

Card tile:
- Artwork harus dominan.
- Nomor kartu dan nama dapat terbaca tanpa membuka detail.
- Hover desktop: sedikit lift/tilt yang halus, tombol quick favorite muncul bila tidak mengganggu.
- Mobile: jangan mengandalkan hover; aksi tersedia melalui menu atau detail.
- Jangan memotong kartu secara ekstrem hanya agar muat lebih banyak.

States wajib:
- loading skeleton dengan proporsi kartu yang benar
- empty collection
- no search result
- error state dan retry
- pagination/loading more

### 7.3 Card detail

**Tujuan:** menikmati satu kartu secara fokus.

Desktop:
- Kartu besar sebagai fokus pada sisi kiri/tengah.
- Metadata dan actions pada panel samping.
- Background netral dan tenang; jangan memberi efek glow berlebihan di sekitar kartu.

Mobile:
- Kartu berada di bagian atas dan tetap proporsional.
- Detail dan actions berada di bawahnya.
- Actions tidak boleh terpotong atau tersembunyi di luar viewport.

Actions:
- Edit
- Add/remove favorite
- Add/remove from album
- Download PNG
- Share (sesuai izin)
- Delete dengan confirmation dialog

### 7.4 Create card flow

Dukung dua jalur dengan UX konsisten:

**Quick / Automatic**
1. Upload atau pilih foto yang sudah ada.
2. Pilih template/rarity.
3. Sistem memberi nilai default/saran yang dapat diedit.
4. Preview.
5. Save to Pokédex dan pilih album opsional.

**Manual editor**
1. Upload atau pilih foto.
2. Pilih template.
3. Atur crop, zoom, dan posisi foto.
4. Isi nama, HP, type, rarity, skill, description/flavor text.
5. Atur pilihan visual yang memang didukung template.
6. Preview.
7. Save.

Creation mode harus bisa dipilih oleh pengguna; jangan memaksa satu mode. Automatic tidak berarti AI harus mengubah atau menafsirkan wajah. Gunakan default/saran berdasarkan metadata atau input pengguna. Seluruh nilai otomatis harus dapat diedit.

### 7.5 Card editor

- Desktop: area preview kartu di satu sisi dan form di sisi lain.
- Mobile: preview di bagian atas, field form dikelompokkan dengan jelas.
- Preview memakai rasio kartu yang sama dengan export.
- Kontrol crop harus jelas: drag to reposition, zoom, reset.
- Perubahan field memperbarui preview secara responsif.
- Gunakan field labels permanen; jangan hanya mengandalkan placeholder.
- Tampilkan batas karakter dan validasi jika diperlukan.
- Tombol Save/Cancel memiliki state loading dan error.

### 7.6 Binder / Albums

**Tujuan:** menyusun kartu menjadi koleksi bertema.

- Album ditampilkan dengan cover card/foto pilihan, nama, jumlah kartu, dan visibility.
- Detail album dapat memiliki layout binder/halaman koleksi.
- Sediakan reordering kartu bila implementasi drag-and-drop tersedia secara aksesibel.
- Album dapat berisi banyak kartu; satu kartu dapat berada di lebih dari satu album.
- Sediakan empty state `Add cards to this binder`.
- Jangan mengunci desain menjadi grid sembilan slot yang meniru produk tertentu; buat layout binder orisinal.

### 7.7 Photo library

- Menampilkan semua foto original yang dimiliki user.
- Status sederhana: used in cards / not used.
- Upload multiple files dengan progress.
- Foto original private.
- Delete foto harus memberi peringatan jika foto masih dipakai oleh kartu.
- Thumbnail menjadi sumber tampilan grid; jangan selalu mengirim original ukuran penuh.

### 7.8 Sharing

- Shared album memakai halaman publik yang tetap membawa identitas MEMODEX.
- Tampilkan nama album, deskripsi, jumlah kartu, dan kartu yang dibagikan.
- Jangan menampilkan email, file path, atau metadata privat.
- Token panjang dan sulit ditebak; dukung revoke dan expiry.
- Public sharing hanya membuka album yang secara eksplisit dibagikan. Bukan membuka seluruh koleksi user.

### 7.9 Profile / storage settings

- Profile information.
- Password/security.
- Storage usage bar dan batas kuota.
- Account privacy.
- Session/logout.

## 8. Card design system

### Physical format

- Finished card proportion: **63 × 88 mm**.
- Raster export target: **744 × 1039 px at 300 DPI** (rounded pixel dimensions).
- Color: sRGB.
- Primary export: PNG.
- Keep important text/art inside safe area; target 3 mm inset unless an existing user-provided template defines a different safe area.
- Do not stretch source photos. Use crop/position controls with `object-fit: cover` behavior.

### Card layers

A template should be composed from independently configurable layers:

1. Base card / background.
2. Border/frame.
3. Photo artwork and crop window.
4. Name and HP header.
5. Type/rarity markers.
6. Skill/description text.
7. Footer, card number, set label.
8. Foil/holographic overlay (optional, clipped to defined regions).
9. Decorative texture/noise (optional, subtle).

### Template requirements

- Template version is immutable once used by saved cards, or saved cards retain a snapshot of the relevant template config.
- New template versions must not silently alter existing cards.
- Support user-provided template assets where available.
- No random decoration, random stats, random rarity, or random skills.
- All essential text must remain legible at card size.
- Provide at least one coherent BASIC template before implementing multiple rarities.

### Card interactions

- Hover effects should be restrained and disabled/reduced when `prefers-reduced-motion` is enabled.
- Tilt effect only on desktop pointer devices and only when it does not interfere with clicking.
- Holographic effects should be localized and use CSS masks/gradients sparingly.
- Do not render the card as an unstructured pile of CSS gradients.

## 9. Component library

Create reusable components using existing project conventions. Expected component families:

- `AppShell`, `Sidebar`, `MobileBottomNav`, `Topbar`, `Breadcrumbs`
- `PageHeader`, `SectionHeading`, `EmptyState`, `ErrorState`, `Skeleton`
- `CardTile`, `CardPreview`, `CardDetailPanel`, `RarityLabel`, `TypeBadge`
- `SearchInput`, `FilterPopover`/`FilterSheet`, `SortSelect`
- `PhotoUploader`, `PhotoGrid`, `CropControls`
- `CardEditorForm`, `TemplatePicker`, `LiveCardPreview`
- `AlbumTile`, `BinderGrid`, `ShareDialog`
- `ConfirmDialog`, `Toast`, `DropdownMenu`

Use one consistent icon library already installed in the project. If none exists, select one lightweight icon set and use it consistently. Do not mix emoji, unrelated icon sets, and hand-drawn icons as UI controls.

## 10. Interaction and motion

- Typical hover/focus transition: 140–220 ms.
- Page-level movement should be subtle: fade/short translate, not elaborate cinematic transitions.
- Use clear focus rings and keyboard navigation.
- Loading states must preserve layout to reduce content shift.
- Toasts should confirm actions without blocking the workflow.
- Destructive actions require confirmation.
- Keep state persistent only where useful; do not surprise users with hidden automatic changes.
- Respect `prefers-reduced-motion`.

## 11. Accessibility and usability

- Meet WCAG AA contrast for normal text where practical.
- All form fields have visible labels and meaningful error messages.
- Buttons have accessible names.
- Icon-only actions require tooltips/aria-labels.
- Keyboard users can access navigation, dialogs, menus, filters, and card actions.
- Dialogs trap focus appropriately and return focus on close.
- Do not use color alone to communicate rarity, status, or errors.
- Tap targets on mobile should be approximately 44 × 44 px or larger.

## 12. Empty, loading, and error states

Every data-driven page needs intentional states:

- **First-use empty:** useful illustration or a restrained graphic, one sentence, one clear CTA.
- **No search results:** explain that no matches were found dan offer clear filters.
- **Loading:** skeletons reflecting the final layout.
- **Upload error:** explain accepted formats/size and allow retry.
- **Storage full:** show usage and a specific path to resolve it.
- **Permission error:** explain that the item is private or unavailable without revealing whether another user's private record exists.
- **Server error:** friendly message and retry action; no stack trace in UI.

Avoid generic empty-state illustrations from random stock packs. Prefer simple custom SVG/icon composition that matches the MEMODEX visual language.

## 13. Anti-AI-slop checklist — mandatory

Before accepting any screen, verify all of the following:

- [ ] The screen has a clear hierarchy and one obvious primary action.
- [ ] Actual product content (cards/photos/albums) is the visual focus.
- [ ] Spacing, alignment, card proportions, and typography are consistent.
- [ ] No arbitrary gradient blobs, neon glows, floating glass cards, or decorative circles.
- [ ] No excessive pills/badges on every item.
- [ ] No unnecessary KPI cards, charts, or fake activity data.
- [ ] No invented people, sample statistics, or fake user content in production states.
- [ ] Icons come from one consistent icon family; no emoji as navigation icons.
- [ ] Buttons and controls work; no decorative controls pretending to be interactive.
- [ ] Responsive behavior has been checked at mobile, tablet, and desktop widths.
- [ ] Empty, loading, error, and success states are designed.
- [ ] No copyrighted Pokémon logo, official artwork, or exact screen recreation is included.

## 14. Content and sample data rules

- Use believable, clearly labeled demo data only in seeders/development previews.
- Do not hardcode demo cards into production UI.
- Use neutral sample names such as `Sample Card 001` only where actual user content is unavailable.
- Never invent that a user has uploaded photos or owns a particular card.
- Keep UI copy concise, warm, and collector-oriented. Avoid excessive game jargon.

## 15. Acceptance criteria for design implementation

The design is ready when:

1. The main shell looks intentional on desktop and mobile.
2. Pokédex grid makes cards recognizable and easy to scan.
3. Card detail gives the artwork enough room.
4. Create flow is understandable without a tutorial.
5. Live preview and final export preserve the same card proportions and layout.
6. Rarity is communicated with restrained, distinct visual treatment.
7. Empty/loading/error states are as carefully designed as populated screens.
8. The UI feels like an original collectible archive, not a copied game UI or a generic AI-generated SaaS template.

---

**Source of truth:** If a screen or component conflicts with this document, follow this document unless the user explicitly approves a change.
