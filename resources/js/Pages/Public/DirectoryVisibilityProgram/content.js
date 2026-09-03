export function getDirectoryProgramContent(isEn) {
    return {
        /*
|--------------------------------------------------------------------------
| HERO
|--------------------------------------------------------------------------
*/

        hero: {
            badge: isEn
                ? "NOW ACCEPTING PARTICIPATING COMPANIES"
                : "PENDAFTARAN PERUSAHAAN DIBUKA",

            headline: isEn
                ? "Transforming Industry Capabilities\ninto Global Business Opportunities"
                : "Mengubah Kapabilitas Industri\nMenjadi Peluang Bisnis Global",

            title: "DIGESTEX Readable-AI Profile\n& Visibility Program",

            ecosystem: "Global Textile Intelligence Ecosystem",

            tagline: isEn
                ? "Make Your Company Easier to Understand, Discover, and Connect."
                : "Membuat Perusahaan Lebih Mudah Dipahami, Ditemukan, dan Terhubung.",

            description: isEn
                ? "Structure your company capabilities into a Readable-AI Profile and Digital Company Passport™ — helping buyers, search systems, and AI better understand your company while strengthening digital and global business visibility."
                : "Strukturkan kapabilitas perusahaan Anda menjadi Readable-AI Profile dan Digital Company Passport™ — membantu buyer, sistem pencarian, dan AI memahami perusahaan Anda dengan lebih baik sekaligus memperkuat visibilitas digital dan global.",

            joinButton: isEn ? "JOIN THE PROGRAM" : "IKUTI PROGRAM",

            learnButton: isEn ? "EXPLORE THE PROGRAM" : "PELAJARI PROGRAM",

            journeyTitle: isEn
                ? "From Company Information to Global Business Visibility"
                : "Dari Informasi Perusahaan Menuju Visibilitas Bisnis Global",

            journey: [
                {
                    icon: "directory",
                    color: "slate",

                    title: isEn
                        ? "Company Information"
                        : "Informasi Perusahaan",
                },

                {
                    icon: "ai",
                    color: "violet",

                    title: "Readable-AI Profile",
                },

                {
                    icon: "passport",
                    color: "blue",

                    title: "Digital Company Passport™",
                },

                {
                    icon: "verified",
                    color: "emerald",

                    title: isEn
                        ? "Verified & Visible"
                        : "Terverifikasi & Terlihat",
                },

                {
                    icon: "matching",
                    color: "amber",

                    title: "Business Discovery & Matching",
                },

                {
                    icon: "global",
                    color: "cyan",

                    title: isEn
                        ? "Global Business Visibility"
                        : "Visibilitas Bisnis Global",
                },
            ],
        },

        /*
|--------------------------------------------------------------------------
| HERO CTA
|--------------------------------------------------------------------------
*/

        cta: {
            badge: isEn
                ? "START YOUR DIGITAL JOURNEY"
                : "MULAI PERJALANAN DIGITAL ANDA",

            title: isEn
                ? "Turn Your Company Information Into Digital Business Visibility."
                : "Ubah Informasi Perusahaan Menjadi Visibilitas Bisnis Digital.",

            description: isEn
                ? "Join the DIGESTEX Readable-AI Profile & Visibility Program and build a structured digital identity that helps your company become easier to understand, discover, trust, and connect with."
                : "Bergabunglah dengan DIGESTEX Readable-AI Profile & Visibility Program dan bangun identitas digital yang terstruktur agar perusahaan Anda lebih mudah dipahami, ditemukan, dipercaya, dan terhubung.",

            benefits: [
                isEn
                    ? "Readable-AI Company Profile"
                    : "Readable-AI Company Profile",

                isEn
                    ? "Professional Digital Company Passport™"
                    : "Digital Company Passport™ Profesional",

                isEn
                    ? "Improved Digital Discoverability"
                    : "Discoverability Digital yang Lebih Baik",

                isEn
                    ? "Global Business Visibility"
                    : "Visibilitas Bisnis Global",

                isEn
                    ? "Business Matching™ Readiness"
                    : "Kesiapan Business Matching™",
            ],

            primaryButton: isEn ? "JOIN THE PROGRAM" : "IKUTI PROGRAM",

            secondaryButton: isEn ? "LEARN MORE" : "PELAJARI PROGRAM",

            closing: isEn
                ? "Make Your Company Easier to Understand. Easier to Discover. Ready for Greater Opportunities."
                : "Buat Perusahaan Anda Lebih Mudah Dipahami. Lebih Mudah Ditemukan. Lebih Siap Meraih Peluang.",

            commitment: isEn
                ? "DIGESTEX is building a trusted Global Textile Intelligence Ecosystem where structured business information becomes the foundation for visibility, intelligence, and global business opportunities."
                : "DIGESTEX membangun Global Textile Intelligence Ecosystem yang terpercaya, di mana informasi bisnis yang terstruktur menjadi fondasi bagi visibilitas, intelligence, dan peluang bisnis global.",

            signature: "Global Textile Intelligence Ecosystem",
        },

        /*
|--------------------------------------------------------------------------
| PROGRAM OVERVIEW
|--------------------------------------------------------------------------
*/

        program: {
            badge: isEn ? "PROGRAM OVERVIEW" : "GAMBARAN PROGRAM",

            title: isEn
                ? "What You'll Receive from This Program"
                : "Yang Akan Anda Peroleh Melalui Program Ini",

            description: isEn
                ? "The DIGESTEX Digital Directory & Visibility Program helps companies build a trusted digital identity, improve visibility, strengthen credibility, and unlock long-term business opportunities."
                : "DIGESTEX Digital Directory & Visibility Program membantu perusahaan membangun identitas digital yang terpercaya, meningkatkan visibilitas, memperkuat kredibilitas, serta membuka peluang bisnis jangka panjang.",

            items: [
                {
                    icon: "passport",

                    title: "Digital Company Passport™",

                    description: isEn
                        ? "Your official digital business identity inside the DIGESTEX ecosystem."
                        : "Identitas digital resmi perusahaan Anda di dalam ekosistem DIGESTEX.",
                },

                {
                    icon: "verified",

                    title: isEn
                        ? "Verified Company"
                        : "Perusahaan Terverifikasi",

                    description: isEn
                        ? "Increase credibility through trusted company verification."
                        : "Meningkatkan kredibilitas melalui proses verifikasi perusahaan.",
                },

                {
                    icon: "visibility",

                    title: isEn ? "Business Visibility" : "Visibilitas Bisnis",

                    description: isEn
                        ? "Become easier to discover by buyers, sourcing teams, and strategic partners."
                        : "Membantu perusahaan lebih mudah ditemukan oleh buyer, tim sourcing, dan mitra strategis.",
                },

                {
                    icon: "score",

                    title: "Visibility Score™",

                    description: isEn
                        ? "Measure profile completeness and digital visibility."
                        : "Mengukur kelengkapan profil dan tingkat visibilitas digital perusahaan.",
                },

                {
                    icon: "matching",

                    title: "Smart Business Matching™",

                    description: isEn
                        ? "Prepare your company for future sourcing and business matching opportunities."
                        : "Mempersiapkan perusahaan mengikuti peluang sourcing dan business matching di masa depan.",
                },

                {
                    icon: "update",

                    title: isEn
                        ? "Continuous Profile Update"
                        : "Profil Selalu Dapat Diperbarui",

                    description: isEn
                        ? "Keep your company information accurate and always up to date."
                        : "Menjaga informasi perusahaan tetap akurat dan selalu diperbarui.",
                },
            ],
        },
        /*
/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

        summary: {
            badge: isEn
                ? "WHY READABLE-AI VISIBILITY MATTERS"
                : "MENGAPA READABLE-AI VISIBILITY PENTING",

            title: isEn
                ? "Prepare Your Company for the Next Generation of Business"
                : "Persiapkan Perusahaan Anda untuk Generasi Bisnis Berikutnya",

            description: isEn
                ? "The textile industry is becoming increasingly digital, searchable, connected, and AI-readable. Companies need to prepare their digital identity and capabilities accordingly."
                : "Industri tekstil semakin digital, semakin mudah dicari, semakin terhubung, dan semakin membutuhkan informasi yang dapat dibaca serta dipahami oleh AI. Perusahaan perlu mempersiapkan identitas digital dan kapabilitasnya untuk menghadapi perubahan tersebut.",

            subtitle: "DIGESTEX Readable-AI Profile & Visibility Program",

            short: isEn
                ? "The textile industry is becoming increasingly digital, searchable, connected, and AI-readable. Companies need to prepare their digital identity and capabilities accordingly."
                : "Industri tekstil semakin digital, semakin mudah dicari, semakin terhubung, dan semakin membutuhkan informasi yang dapat dibaca serta dipahami oleh AI. Perusahaan perlu mempersiapkan identitas digital dan kapabilitasnya untuk menghadapi perubahan tersebut.",

            button: isEn ? "READ MORE" : "BACA SELENGKAPNYA",

            buttonClose: isEn ? "SHOW LESS" : "TUTUP",

            detailTitle: isEn
                ? "Why Companies Need to Become Readable-AI"
                : "Mengapa Perusahaan Perlu Menjadi Readable-AI",

            detail: [
                isEn
                    ? "The textile industry is becoming increasingly digital, searchable, connected, and AI-readable. Companies need to prepare their digital identity and capabilities accordingly."
                    : "Industri tekstil semakin digital, semakin mudah dicari, semakin terhubung, dan semakin membutuhkan informasi yang dapat dibaca serta dipahami oleh AI. Perusahaan perlu mempersiapkan identitas digital dan kapabilitasnya untuk menghadapi perubahan tersebut.",

                isEn
                    ? "DIGESTEX provides the infrastructure to help companies transform their industry capabilities into structured Readable-AI Profiles."
                    : "DIGESTEX menyediakan infrastrukturnya untuk membantu perusahaan mentransformasikan kapabilitas industrinya menjadi Readable-AI Profile yang terstruktur.",

                isEn
                    ? "A structured Readable-AI Profile makes company capabilities easier to discover, understand, and evaluate across digital channels."
                    : "Readable-AI Profile yang terstruktur membuat kapabilitas perusahaan lebih mudah ditemukan, dipahami, dan dievaluasi melalui berbagai kanal digital.",

                isEn
                    ? "Structured and trusted information creates a stronger digital foundation for connecting companies with relevant buyers, sourcing opportunities, strategic partners, and global business networks."
                    : "Informasi yang terstruktur dan terpercaya menciptakan fondasi digital yang lebih kuat untuk menghubungkan perusahaan dengan buyer yang relevan, peluang sourcing, mitra strategis, dan jaringan bisnis global.",

                isEn
                    ? "Your Readable-AI Profile becomes part of the DIGESTEX Global Textile Intelligence Ecosystem — an infrastructure designed to connect industry capabilities, intelligence, technology, and business opportunities."
                    : "Readable-AI Profile perusahaan Anda menjadi bagian dari DIGESTEX Global Textile Intelligence Ecosystem — sebuah infrastruktur yang dirancang untuk menghubungkan kapabilitas industri, intelligence, teknologi, dan peluang bisnis.",

                isEn
                    ? "The question is no longer whether companies should become digitally visible, but whether they are ready to be discovered in the next generation of business."
                    : "Pertanyaannya bukan lagi apakah perusahaan perlu memiliki visibilitas digital, tetapi apakah perusahaan siap ditemukan dalam generasi bisnis berikutnya.",
            ],

            closing: {
                title: isEn
                    ? "Is Your Company Ready to Be Discovered?"
                    : "Apakah Perusahaan Anda Siap untuk Ditemukan?",

                description: isEn
                    ? "Your capabilities already exist. DIGESTEX helps transform them into a structured Readable-AI Profile — making your company easier to discover, understand, trust, and connect with relevant business opportunities."
                    : "Kapabilitas perusahaan Anda sudah ada. DIGESTEX membantu mentransformasikannya menjadi Readable-AI Profile yang terstruktur — sehingga perusahaan lebih mudah ditemukan, dipahami, dipercaya, dan terhubung dengan peluang bisnis yang relevan.",

                button: isEn ? "JOIN THE PROGRAM" : "IKUTI PROGRAM",
            },
        },
        /*
|--------------------------------------------------------------------------
| DIGITAL COMPANY PASSPORT
|--------------------------------------------------------------------------
*/

        passport: {
            badge: isEn
                ? "DIGITAL COMPANY PASSPORT™"
                : "DIGITAL COMPANY PASSPORT™",

            title: isEn
                ? "Turn Your Company Capabilities into a Digital Business Identity"
                : "Ubah Kapabilitas Perusahaan Menjadi Identitas Bisnis Digital",

            description: isEn
                ? "Your Readable-AI Profile becomes the foundation of a Digital Company Passport™ — a structured digital business identity that brings together your company capabilities, products, technologies, certifications, production information, and market readiness."
                : "Readable-AI Profile menjadi fondasi Digital Company Passport™ — identitas bisnis digital yang terstruktur dan menyatukan kapabilitas perusahaan, produk, teknologi, sertifikasi, informasi produksi, serta kesiapan pasar perusahaan.",

            introduction: {
                title: isEn
                    ? "What is a Digital Company Passport™?"
                    : "Apa itu Digital Company Passport™?",

                description: isEn
                    ? "A Digital Company Passport™ transforms structured company information into a comprehensive digital business identity — helping buyers, sourcing teams, search systems, and AI understand what your company does, what you offer, where you operate, and where your capabilities fit."
                    : "Digital Company Passport™ mengubah informasi perusahaan yang terstruktur menjadi identitas bisnis digital yang komprehensif — membantu buyer, tim sourcing, sistem pencarian, dan AI memahami apa yang perusahaan Anda lakukan, apa yang Anda tawarkan, di mana Anda beroperasi, dan di mana kapabilitas Anda sesuai.",
            },

            features: [
                {
                    title: isEn ? "Company Identity" : "Identitas Perusahaan",

                    description: isEn
                        ? "Present your company identity, business profile, location, contacts, ownership, and essential corporate information in a structured digital format."
                        : "Tampilkan identitas perusahaan, profil bisnis, lokasi, kontak, kepemilikan, dan informasi utama perusahaan dalam format digital yang terstruktur.",
                },

                {
                    title: isEn
                        ? "Products & Capabilities"
                        : "Produk & Kapabilitas",

                    description: isEn
                        ? "Clearly communicate what your company produces, supplies, manufactures, or provides, together with its areas of specialization."
                        : "Komunikasikan secara jelas produk yang diproduksi, dipasok, atau layanan yang diberikan perusahaan beserta bidang spesialisasinya.",
                },

                {
                    title: isEn
                        ? "Production & Capacity"
                        : "Produksi & Kapasitas",

                    description: isEn
                        ? "Provide a clearer view of production capabilities, capacity, facilities, and manufacturing scale relevant to potential business requirements."
                        : "Berikan gambaran yang lebih jelas mengenai kemampuan produksi, kapasitas, fasilitas, dan skala manufaktur yang relevan dengan kebutuhan bisnis.",
                },

                {
                    title: isEn
                        ? "Machinery & Technology"
                        : "Mesin & Teknologi",

                    description: isEn
                        ? "Present key machinery, production technologies, processes, and manufacturing infrastructure that support your capabilities."
                        : "Tampilkan mesin utama, teknologi produksi, proses, dan infrastruktur manufaktur yang mendukung kapabilitas perusahaan.",
                },

                {
                    title: isEn
                        ? "Certifications & Compliance"
                        : "Sertifikasi & Kepatuhan",

                    description: isEn
                        ? "Highlight relevant certifications, standards, testing, compliance, and verification information that strengthen business credibility."
                        : "Tampilkan sertifikasi, standar, pengujian, kepatuhan, dan informasi verifikasi yang relevan untuk memperkuat kredibilitas bisnis.",
                },

                {
                    title: isEn
                        ? "Markets & Export Experience"
                        : "Pasar & Pengalaman Ekspor",

                    description: isEn
                        ? "Show your market coverage, export experience, target markets, and readiness to engage with international buyers and business partners."
                        : "Tampilkan cakupan pasar, pengalaman ekspor, target pasar, dan kesiapan perusahaan untuk berinteraksi dengan buyer serta mitra bisnis internasional.",
                },
            ],

            closing: {
                badge: isEn
                    ? "YOUR DIGITAL BUSINESS IDENTITY"
                    : "IDENTITAS BISNIS DIGITAL ANDA",

                title: isEn
                    ? "Make Your Capabilities Visible, Understandable, and Discoverable"
                    : "Jadikan Kapabilitas Anda Terlihat, Dipahami, dan Mudah Ditemukan",

                description: isEn
                    ? "Your Digital Company Passport™ brings together the structured information behind your company identity, products, capabilities, technologies, certifications, production capacity, and market experience — creating a stronger digital presence for discovery, credibility, and global business opportunities."
                    : "Digital Company Passport™ menyatukan informasi terstruktur mengenai identitas perusahaan, produk, kapabilitas, teknologi, sertifikasi, kapasitas produksi, dan pengalaman pasar — menciptakan kehadiran digital yang lebih kuat untuk discovery, kredibilitas, dan peluang bisnis global.",

                primaryButton: isEn
                    ? "BUILD YOUR COMPANY PASSPORT"
                    : "BANGUN COMPANY PASSPORT ANDA",

                secondaryButton: isEn
                    ? "LEARN ABOUT THE PROGRAM"
                    : "PELAJARI PROGRAM",

                quote: isEn
                    ? "Your company profile is no longer just information. It becomes a structured digital business identity that can be discovered, understood, and connected."
                    : "Profil perusahaan Anda bukan lagi sekadar informasi. Profil tersebut menjadi identitas bisnis digital yang terstruktur, dapat ditemukan, dipahami, dan dihubungkan.",

                commitment: isEn
                    ? "DIGESTEX is building a Global Textile Intelligence Ecosystem where structured, reliable, and meaningful company information helps businesses become more discoverable and creates stronger connections to global business opportunities."
                    : "DIGESTEX membangun Global Textile Intelligence Ecosystem di mana informasi perusahaan yang terstruktur, terpercaya, dan bermakna membantu perusahaan menjadi lebih mudah ditemukan serta menciptakan koneksi yang lebih kuat dengan peluang bisnis global.",
            },
        },
        /*
|--------------------------------------------------------------------------
| BENEFITS
|--------------------------------------------------------------------------
*/

        benefits: {
            badge: isEn ? "BUSINESS BENEFITS" : "MANFAAT BISNIS",

            title: isEn
                ? "Turn Digital Visibility into Business Opportunity"
                : "Ubah Visibilitas Digital Menjadi Peluang Bisnis",

            description: isEn
                ? "The DIGESTEX Readable-AI Profile & Visibility Program goes beyond listing your company. It structures your capabilities into a meaningful digital business identity designed to improve discoverability, strengthen credibility, and position your company for relevant business opportunities."
                : "DIGESTEX Readable-AI Profile & Visibility Program lebih dari sekadar menampilkan perusahaan. Program ini menstrukturkan kapabilitas perusahaan menjadi identitas bisnis digital yang bermakna untuk meningkatkan discoverability, memperkuat kredibilitas, dan memposisikan perusahaan pada peluang bisnis yang relevan.",

            items: [
                {
                    icon: "visibility",

                    title: isEn
                        ? "Strengthen Global Visibility"
                        : "Memperkuat Visibilitas Global",

                    description: isEn
                        ? "Present your company capabilities in a structured digital format that makes your business easier to discover across the DIGESTEX ecosystem."
                        : "Tampilkan kapabilitas perusahaan dalam format digital yang terstruktur agar bisnis Anda lebih mudah ditemukan di dalam ekosistem DIGESTEX.",
                },

                {
                    icon: "discover",

                    title: isEn
                        ? "Become More Discoverable"
                        : "Lebih Mudah Ditemukan",

                    description: isEn
                        ? "Structure your products, capabilities, markets, technologies, and certifications so buyers, search systems, and AI can better understand where your company fits."
                        : "Strukturkan produk, kapabilitas, pasar, teknologi, dan sertifikasi agar buyer, sistem pencarian, dan AI dapat lebih memahami posisi serta relevansi perusahaan Anda.",
                },

                {
                    icon: "credibility",

                    title: isEn
                        ? "Strengthen Digital Credibility"
                        : "Memperkuat Kredibilitas Digital",

                    description: isEn
                        ? "Structured and verified company information provides a stronger foundation for trust before a business conversation begins."
                        : "Informasi perusahaan yang terstruktur dan terverifikasi memberikan fondasi yang lebih kuat untuk membangun kepercayaan sebelum komunikasi bisnis dimulai.",
                },

                {
                    icon: "matching",

                    title: isEn
                        ? "Prepare for Smart Business Matching™"
                        : "Mempersiapkan Smart Business Matching™",

                    description: isEn
                        ? "Build the structured company information needed to support future AI-assisted Business Matching and more relevant business connections."
                        : "Bangun informasi perusahaan yang terstruktur sebagai fondasi untuk mendukung AI-assisted Business Matching dan koneksi bisnis yang lebih relevan di masa depan.",
                },

                {
                    icon: "ecosystem",

                    title: isEn
                        ? "Become Part of a Connected Ecosystem"
                        : "Menjadi Bagian dari Ekosistem Terhubung",

                    description: isEn
                        ? "Connect your company identity and capabilities to the DIGESTEX Global Textile Intelligence Ecosystem across industry, sourcing, technology, services, and investment."
                        : "Hubungkan identitas dan kapabilitas perusahaan dengan DIGESTEX Global Textile Intelligence Ecosystem yang mencakup industri, sourcing, teknologi, layanan, dan investasi.",
                },

                {
                    icon: "growth",

                    title: isEn
                        ? "Position for New Opportunities"
                        : "Membuka Posisi untuk Peluang Baru",

                    description: isEn
                        ? "Greater visibility, clearer capability communication, and stronger digital credibility can create more opportunities for business development and international collaboration."
                        : "Visibilitas yang lebih tinggi, komunikasi kapabilitas yang lebih jelas, dan kredibilitas digital yang lebih kuat dapat membuka lebih banyak peluang pengembangan bisnis dan kolaborasi internasional.",
                },
            ],

            closing: {
                title: isEn
                    ? "Make Your Capabilities Visible. Make Your Business Discoverable."
                    : "Jadikan Kapabilitas Anda Terlihat. Jadikan Bisnis Anda Mudah Ditemukan.",

                description: isEn
                    ? "Your Digital Company Passport™ gives your company a structured digital identity within the DIGESTEX ecosystem — helping buyers and business networks understand your capabilities and discover where your company fits."
                    : "Digital Company Passport™ memberikan identitas digital yang terstruktur bagi perusahaan Anda di dalam ekosistem DIGESTEX — membantu buyer dan jaringan bisnis memahami kapabilitas perusahaan serta menemukan posisi yang relevan bagi bisnis Anda.",

                button: isEn ? "JOIN THE PROGRAM" : "IKUTI PROGRAM",
            },
        },

        /*
|--------------------------------------------------------------------------
| DIGITAL TRANSFORMATION JOURNEY
|--------------------------------------------------------------------------
*/

        transformation: {
            badge: isEn
                ? "DIGITAL TRANSFORMATION JOURNEY"
                : "PERJALANAN TRANSFORMASI DIGITAL",

            title: isEn
                ? "From Company Information to Global Business Visibility"
                : "Dari Informasi Perusahaan Menuju Visibilitas Bisnis Global",

            description: isEn
                ? "Your digital journey begins with complete company information and evolves into a structured, trusted, and Readable-AI Profile — helping buyers, search systems, and AI better understand your company, capabilities, and business potential."
                : "Perjalanan digital perusahaan dimulai dari informasi perusahaan yang lengkap dan berkembang menjadi Readable-AI Profile yang terstruktur dan terpercaya — membantu buyer, sistem pencarian, dan AI memahami perusahaan, kapabilitas, serta potensi bisnis Anda dengan lebih baik.",

            steps: [
                {
                    number: "01",

                    title: isEn
                        ? "Complete Your Company Information"
                        : "Lengkapi Informasi Perusahaan",

                    description: isEn
                        ? "Provide complete and accurate information about your company, products, manufacturing capabilities, certifications, production capacity, markets, and business contacts."
                        : "Lengkapi informasi perusahaan secara akurat, termasuk produk, kemampuan manufaktur, sertifikasi, kapasitas produksi, pasar, dan kontak bisnis.",
                },

                {
                    number: "02",

                    title: "Readable-AI Profile",

                    description: isEn
                        ? "Your company information is structured into a Readable-AI Profile so that people, buyers, search systems, and AI can better understand what your company does, what you offer, and where you operate."
                        : "Informasi perusahaan Anda disusun menjadi Readable-AI Profile agar manusia, buyer, sistem pencarian, dan AI dapat lebih mudah memahami apa yang perusahaan Anda lakukan, apa yang ditawarkan, dan di mana perusahaan beroperasi.",
                },

                {
                    number: "03",

                    title: "Digital Company Passport™",

                    description: isEn
                        ? "Your structured company information becomes a professional Digital Company Passport™ — a trusted digital business identity representing your company, capabilities, products, certifications, and market readiness."
                        : "Informasi perusahaan yang telah terstruktur menjadi Digital Company Passport™ — identitas bisnis digital profesional yang merepresentasikan perusahaan, kapabilitas, produk, sertifikasi, dan kesiapan pasar.",
                },

                {
                    number: "04",

                    title: isEn
                        ? "Verification & Trust"
                        : "Verifikasi & Kepercayaan",

                    description: isEn
                        ? "Relevant company information can be verified to strengthen credibility and provide greater confidence to buyers, sourcing teams, investors, and strategic partners."
                        : "Informasi perusahaan yang relevan dapat diverifikasi untuk memperkuat kredibilitas dan memberikan keyakinan lebih besar kepada buyer, sourcing team, investor, dan mitra strategis.",
                },

                {
                    number: "05",

                    title: "Visibility Score™",

                    description: isEn
                        ? "Your digital presence is evaluated through profile completeness, information quality, credibility, and business readiness — helping identify opportunities to improve your company's digital visibility."
                        : "Kehadiran digital perusahaan dievaluasi berdasarkan kelengkapan profil, kualitas informasi, kredibilitas, dan kesiapan bisnis — membantu mengidentifikasi peluang untuk meningkatkan visibilitas digital perusahaan.",
                },

                {
                    number: "06",

                    title: isEn
                        ? "Business Discovery & Opportunities"
                        : "Business Discovery & Peluang Bisnis",

                    description: isEn
                        ? "A stronger and more understandable digital identity improves the opportunity for your company to be discovered, shortlisted, contacted, and considered for sourcing, Business Matching™, and global collaboration."
                        : "Identitas digital yang lebih kuat dan mudah dipahami meningkatkan peluang perusahaan ditemukan, masuk shortlist, dihubungi, dan dipertimbangkan untuk sourcing, Business Matching™, dan kolaborasi global.",
                },

                {
                    number: "07",

                    title: "Executive Intelligence™",

                    description: isEn
                        ? "The journey continues as your structured company information becomes a foundation for future Executive Dashboard™, AI Insight™, market intelligence, and other DIGESTEX intelligence services."
                        : "Perjalanan berlanjut ketika informasi perusahaan yang telah terstruktur menjadi fondasi bagi Executive Dashboard™, AI Insight™, market intelligence, dan berbagai layanan intelligence DIGESTEX di masa depan.",
                },
            ],

            closing: {
                title: isEn
                    ? "Your Digital Journey Becomes Your Business Advantage"
                    : "Perjalanan Digital Menjadi Keunggulan Bisnis Anda",

                description: isEn
                    ? "DIGESTEX helps transform company information into a structured digital identity that can be understood, discovered, trusted, and connected — creating a stronger foundation for global business opportunities."
                    : "DIGESTEX membantu mengubah informasi perusahaan menjadi identitas digital yang terstruktur, dapat dipahami, ditemukan, dipercaya, dan terhubung — menciptakan fondasi yang lebih kuat untuk peluang bisnis global.",

                button: isEn
                    ? "START YOUR DIGITAL JOURNEY"
                    : "MULAI PERJALANAN DIGITAL ANDA",
            },
        },

        /*
|--------------------------------------------------------------------------
| MEMBERSHIP JOURNEY
|--------------------------------------------------------------------------
*/

        membership: {
            badge: isEn ? "DIGESTEX JOURNEY" : "PERJALANAN DIGESTEX",

            title: isEn
                ? "From Digital Identity to Global Business Opportunities"
                : "Dari Identitas Digital Menuju Peluang Bisnis Global",

            description: isEn
                ? "Your DIGESTEX journey begins by structuring your company information into a Readable-AI Profile and building a trusted Digital Company Passport™. As your digital presence grows, your company can progress toward greater visibility, intelligence, connectivity, and business opportunities."
                : "Perjalanan DIGESTEX dimulai dengan menyusun informasi perusahaan menjadi Readable-AI Profile dan membangun Digital Company Passport™ yang terpercaya. Seiring berkembangnya kehadiran digital perusahaan, Anda dapat melangkah menuju visibilitas, intelligence, konektivitas, dan peluang bisnis yang lebih besar.",

            levels: [
                {
                    level: "01",

                    title: isEn ? "Readable-AI Profile" : "Readable-AI Profile",

                    subtitle: isEn
                        ? "Make Your Company Understandable"
                        : "Membuat Perusahaan Lebih Mudah Dipahami",

                    description: isEn
                        ? "Structure your company information so that buyers, search systems, and AI can clearly understand your identity, products, capabilities, markets, and business focus."
                        : "Susun informasi perusahaan agar buyer, sistem pencarian, dan AI dapat memahami dengan jelas identitas, produk, kapabilitas, pasar, dan fokus bisnis perusahaan.",

                    features: [
                        isEn
                            ? "Structured Company Information"
                            : "Informasi Perusahaan yang Terstruktur",

                        isEn
                            ? "Products & Capabilities"
                            : "Produk & Kapabilitas",

                        isEn
                            ? "Business & Market Information"
                            : "Informasi Bisnis & Pasar",

                        isEn
                            ? "AI-Readable Company Profile"
                            : "Profil Perusahaan yang AI-Readable",
                    ],

                    highlight: false,
                },

                {
                    level: "02",

                    title: "Digital Company Passport™",

                    subtitle: isEn
                        ? "Build a Trusted Digital Business Identity"
                        : "Bangun Identitas Bisnis Digital yang Terpercaya",

                    description: isEn
                        ? "Transform your structured company information into a professional Digital Company Passport™ representing your company identity, capabilities, products, certifications, and market readiness."
                        : "Transformasikan informasi perusahaan yang telah terstruktur menjadi Digital Company Passport™ yang profesional untuk merepresentasikan identitas, kapabilitas, produk, sertifikasi, dan kesiapan pasar perusahaan.",

                    features: [
                        isEn
                            ? "Professional Digital Company Profile"
                            : "Profil Digital Perusahaan Profesional",

                        isEn
                            ? "Digital Company Passport™"
                            : "Digital Company Passport™",

                        isEn
                            ? "Company & Product Visibility"
                            : "Visibilitas Perusahaan & Produk",

                        isEn
                            ? "Business Contact Visibility"
                            : "Visibilitas Kontak Bisnis",
                    ],

                    highlight: true,
                },

                {
                    level: "03",

                    title: isEn
                        ? "Verified & Visible Company"
                        : "Perusahaan Terverifikasi & Terlihat",

                    subtitle: isEn
                        ? "Strengthen Trust and Discoverability"
                        : "Perkuat Kepercayaan & Discoverability",

                    description: isEn
                        ? "Strengthen your digital credibility through verified information and improve your company's discoverability across the DIGESTEX ecosystem."
                        : "Perkuat kredibilitas digital melalui informasi yang terverifikasi dan tingkatkan discoverability perusahaan di dalam ekosistem DIGESTEX.",

                    features: [
                        isEn
                            ? "Verified Company Information"
                            : "Informasi Perusahaan Terverifikasi",

                        isEn ? "Verification Status" : "Status Verifikasi",

                        isEn ? "Visibility Score™" : "Visibility Score™",

                        isEn
                            ? "Improved Business Discoverability"
                            : "Discoverability Bisnis yang Lebih Baik",
                    ],

                    highlight: false,
                },

                {
                    level: "04",

                    title: isEn
                        ? "Business Intelligence"
                        : "Business Intelligence",

                    subtitle: isEn
                        ? "Turn Structured Information into Intelligence"
                        : "Ubah Informasi Terstruktur Menjadi Intelligence",

                    description: isEn
                        ? "As your company information becomes richer and more structured, it creates a stronger foundation for company intelligence, market insight, and future AI-powered services."
                        : "Ketika informasi perusahaan menjadi semakin lengkap dan terstruktur, informasi tersebut menjadi fondasi yang lebih kuat untuk company intelligence, market insight, dan layanan berbasis AI di masa depan.",

                    features: [
                        isEn
                            ? "Company Intelligence Profile"
                            : "Company Intelligence Profile",

                        isEn
                            ? "Manufacturing & Production Capabilities"
                            : "Kapabilitas Manufaktur & Produksi",

                        isEn
                            ? "Certifications & Export Markets"
                            : "Sertifikasi & Pasar Ekspor",

                        isEn
                            ? "AI Insight™ & Executive Intelligence™"
                            : "AI Insight™ & Executive Intelligence™",
                    ],

                    highlight: false,
                },

                {
                    level: "05",

                    title: isEn
                        ? "Global Business Connectivity"
                        : "Konektivitas Bisnis Global",

                    subtitle: isEn
                        ? "Turn Visibility into Business Opportunities"
                        : "Ubah Visibilitas Menjadi Peluang Bisnis",

                    description: isEn
                        ? "A trusted and understandable digital identity creates a stronger foundation for sourcing, Business Matching™, buyer discovery, strategic partnerships, and global business opportunities."
                        : "Identitas digital yang terpercaya dan mudah dipahami menciptakan fondasi yang lebih kuat untuk sourcing, Business Matching™, buyer discovery, kemitraan strategis, dan peluang bisnis global.",

                    features: [
                        isEn
                            ? "Smart Business Matching™"
                            : "Smart Business Matching™",

                        isEn
                            ? "Buyer & Supplier Connectivity"
                            : "Konektivitas Buyer & Supplier",

                        isEn ? "Sourcing Opportunities" : "Peluang Sourcing",

                        isEn
                            ? "Global Business Visibility"
                            : "Visibilitas Bisnis Global",
                    ],

                    highlight: false,
                },
            ],

            closing: {
                title: isEn
                    ? "Your Digital Identity Is the Beginning"
                    : "Identitas Digital Adalah Awal Perjalanan Anda",

                description: isEn
                    ? "DIGESTEX is designed as a continuous journey. Start by building a Readable-AI Profile and Digital Company Passport™, strengthen trust and visibility, and progressively unlock greater access to intelligence, connectivity, and global business opportunities."
                    : "DIGESTEX dirancang sebagai sebuah perjalanan yang berkelanjutan. Mulailah dengan membangun Readable-AI Profile dan Digital Company Passport™, perkuat kepercayaan dan visibilitas, lalu secara bertahap membuka akses menuju intelligence, konektivitas, dan peluang bisnis global yang lebih besar.",

                button: isEn
                    ? "START YOUR DIGESTEX JOURNEY"
                    : "MULAI PERJALANAN DIGESTEX ANDA",
            },
        },

        /*
|--------------------------------------------------------------------------
| OUR COMMITMENT
|--------------------------------------------------------------------------
*/

        commitment: {
            badge: isEn ? "OUR COMMITMENT" : "KOMITMEN KAMI",

            title: isEn
                ? "Building a Trusted Global Textile Intelligence Ecosystem"
                : "Membangun Global Textile Intelligence Ecosystem yang Terpercaya",

            description: isEn
                ? "DIGESTEX is committed to helping companies transform business information into structured digital identities that can be understood, discovered, trusted, and connected."
                : "DIGESTEX berkomitmen membantu perusahaan mengubah informasi bisnis menjadi identitas digital yang terstruktur, dapat dipahami, ditemukan, dipercaya, dan terhubung.",

            items: [
                {
                    icon: "shield",

                    title: isEn
                        ? "Trusted Information"
                        : "Informasi Terpercaya",

                    description: isEn
                        ? "Structured and verified company information creates a stronger foundation for digital trust."
                        : "Informasi perusahaan yang terstruktur dan terverifikasi menciptakan fondasi yang lebih kuat untuk kepercayaan digital.",
                },

                {
                    icon: "globe",

                    title: isEn ? "Global Visibility" : "Visibilitas Global",

                    description: isEn
                        ? "Help companies become more discoverable to buyers, sourcing teams, investors, and strategic partners."
                        : "Membantu perusahaan lebih mudah ditemukan oleh buyer, sourcing team, investor, dan mitra strategis.",
                },

                {
                    icon: "network",

                    title: isEn
                        ? "Business Connectivity"
                        : "Konektivitas Bisnis",

                    description: isEn
                        ? "Connect trusted company information with relevant people, companies, and future business opportunities."
                        : "Menghubungkan informasi perusahaan yang terpercaya dengan pihak, perusahaan, dan peluang bisnis yang relevan.",
                },

                {
                    icon: "sparkles",

                    title: isEn
                        ? "Continuous Intelligence"
                        : "Intelligence Berkelanjutan",

                    description: isEn
                        ? "Continuously develop new intelligence and AI-enabled services that create greater value from structured business information."
                        : "Terus mengembangkan layanan intelligence dan AI yang menciptakan nilai lebih besar dari informasi bisnis yang terstruktur.",
                },
            ],

            signature: {
                title: "DIGESTEX",

                subtitle: "Global Textile Intelligence Ecosystem",

                quote: isEn
                    ? "Transforming structured company information into visibility, intelligence, and global business opportunities."
                    : "Mengubah informasi perusahaan yang terstruktur menjadi visibilitas, intelligence, dan peluang bisnis global.",

                button: isEn ? "JOIN THE PROGRAM" : "IKUTI PROGRAM",
            },
        },
    };
}
