# MEMODEX — Personal Digital Pokémon Card Collection

**MEMODEX** is a multi-user digital Pokémon card collection platform inspired by the Pokémon Pokédex and Pokémon TCG Pocket, built with **Laravel 12, Blade, Tailwind CSS, Alpine.js, SQLite/MySQL, and private server storage**.

---

## 1. Core Non-Negotiable Rule: 100% Authentic Card Integrity

> **GAMBAR KARTU YANG DIUPLOAD TIDAK BOLEH DIEDIT.**
> Uploaded images are finished, authentic Pokémon cards. The system strictly **NEVER** modifies the artwork, face, character, text, HP, skills, or game stats.
> The system **ONLY adds an OUTER FRAME** around the card as an archival display layer.

Each card entry provides access to two files:
1. **Original Card**: 100% untouched source photo file in original bytes.
2. **Framed Card**: 744 &times; 1039 px high-resolution composite of the untouched card centered inside an outer decorative frame.

---

## 2. The 7 Outer Frame Varieties

All 7 varieties are seeded and configurable in the Admin Panel:
1. **Classic Frame (`classic`)**: Timeless obsidian matting with refined double rim and blue accents.
2. **Silver Frame (`silver`)**: Brushed platinum and cool metallic silver border with dual micro-insets.
3. **Gold Frame (`gold`)**: Rich metallic gold border with warm brass bevels and corner gems.
4. **Electric Frame (`electric`)**: Vibrant electric cyan and royal blue border with energy chevrons.
5. **Holographic Frame (`holographic`)**: Prismatic iridescent foil border with ultraviolet and teal facets.
6. **Rainbow Frame (`rainbow`)**: Radiant spectrum luminescence with multi-tone borders.
7. **Premium Frame (`premium`)**: Museum-grade acrylic slab mount with velvet bevel and gold authenticity plaque.

---

## 3. Key Pages & Features

### A. Authentication
- Register, Login, Forgot Password, Profile Management.
- Multi-user isolation: Every user has their own photos, cards, binders, favorites, and storage quota.

### B. Home Dashboard
- Collector greeting and overview.
- Total cards, Total albums, Favorites, and 500 MB Storage Meter.
- Featured Card showcase.
- Recently added Pokémon cards.
- Quick link to "+ Add Pokémon Cards".

### C. Pokédex
- **Grid View** & **List View** toggle.
- Search by card name or collection number (`MDX-000001`).
- Filter by Outer Frame (All, Classic, Silver, Gold, Electric, Holographic, Rainbow, Premium).
- Filter by Binder / Album.
- Filter by Upload Date (Today, This Week, This Month, All Time).
- Sorting by Newest, Oldest, Card Number, Name.
- Interactive favorite toggle on every card tile.

### D. Card Detail
- Large card viewer with Dual Tabs:
  - **Framed Card Tab**: Shows the high-res composite PNG with the decorative outer frame.
  - **Original Card Tab**: Shows the 100% untouched uploaded original card.
- Download actions:
  - **Download Framed PNG** (`{card_number}_{name}_framed.png`)
  - **Download Original** (`{card_number}_{name}_original.{ext}`)
- **Frame Switcher**: Change outer frame with 1-click re-render without altering the original card file.
- Binder assignment / removal.
- Metadata table (File name, dimensions, file size, archived timestamp, card number).
- Permanent deletion with storage cleanup.

### E. Upload & Create Collection (Two Modes)
- **Automatic Mode (Batch)**:
  - Drag & drop multiple Pokémon card images simultaneously.
  - Select an outer frame to apply to the batch (default: Classic Frame).
  - Optional binder assignment.
  - 1-click batch archive into Pokédex with sequential collection numbers (`MDX-000001`, `MDX-000002`...).
- **Manual Mode (Single Card Studio)**:
  - Upload a new card image or pick from previously uploaded library photos.
  - Interactive live frame preview: clicking any of the 7 frames updates the visual container in real-time.
  - Optional catalog label name.
  - Optional binder assignment.
  - Save & frame card into Pokédex.

### F. Themed Binders / Albums
- Create, edit, and delete binders with custom descriptions and covers.
- Add/remove cards from binders (a card can belong to multiple binders).
- Custom card sort order.
- **Public Sharing**: Generate unique unguessable tokens (`/shared/{token}`) with optional expiration and 1-click revocation. Read-only public showcase scoped strictly to the shared album.

### G. Admin Panel (`/admin`)
Protected by `EnsureUserIsAdmin` middleware (`is_admin` boolean on User model):
- **Dashboard**: Platform metrics (total collectors, cards, binders, private storage).
- **Frame Management (`/admin/frames`)**: List all 7 frames, toggle `is_active`, edit styling config (colors, border widths, plaque labels).
- **User Management (`/admin/users`)**: Search collectors, inspect card counts and storage quota progress, promote/demote administrators.

---

## 4. Development Setup & Credentials

```bash
cd memodex

# Run migrations and seeders
php artisan migrate --seed

# Build frontend assets (Vite + Tailwind CSS + Alpine.js)
npm run build
# Or start Vite dev server
npm run dev

# Start Laravel backend server
php artisan serve --port=8000
```

### Default Accounts Seeded:
- **Collector Account**:
  - Email: `collector@memodex.test`
  - Password: `password`
  - Role: Collector
- **Admin Account**:
  - Email: `admin@memodex.test`
  - Password: `password`
  - Role: Administrator (`/admin`)

---

## 5. Automated Testing Suite

```bash
cd memodex
php artisan test
```

**Results**: 44 passed tests, 134 assertions.
- Validates 100% original card byte integrity (unaltered original file).
- Validates 744 &times; 1039 px high-res outer frame rendering.
- Validates frame switching and re-rendering.
- Validates batch creation in Automatic Mode.
- Validates separate downloads for framed vs original cards.
- Validates multi-user access control, private storage streaming authorization, and token-scoped public album sharing.
- Validates admin panel authorization and frame toggles.
