import WebsiteLayout from "@/Layouts/WebsiteLayout";
import { Link, usePage } from "@inertiajs/react";

export default function About() {
    const { props } = usePage();

    const isEn = props.locale === "en";
    const timeline = [
        {
            year: "2002",
            title: "Industry Association Engagement",
            titleId: "Keterlibatan dalam Asosiasi Industri",

            descriptionEn:
                "Beginning active involvement in Indonesia's textile industry, developing first-hand understanding of manufacturers, suppliers, industry challenges, and the business environment across the textile value chain.",

            descriptionId:
                "Memulai keterlibatan aktif dalam industri tekstil Indonesia, membangun pemahaman langsung mengenai manufacturer, supplier, tantangan industri, dan lingkungan bisnis di seluruh rantai nilai industri tekstil.",
        },

        {
            year: "2004 - 2011",
            title: "International Textile Media & Global Industry Network",
            titleId: "Media Tekstil Internasional & Jaringan Industri Global",

            descriptionEn:
                "Contributing to international textile publications while building relationships with manufacturers, suppliers, buyers, technology providers, and textile industry stakeholders across international markets.",

            descriptionId:
                "Berkontribusi pada publikasi tekstil internasional sekaligus membangun hubungan dengan manufacturer, supplier, buyer, technology provider, dan stakeholder industri tekstil di berbagai pasar internasional.",
        },

        {
            year: "2006 - 2011",
            title: "Government, Trade & Industry Engagement",
            titleId: "Keterlibatan Pemerintah, Perdagangan & Industri",

            descriptionEn:
                "Engaging with government and industry stakeholders while gaining deeper experience in industrial policy, international trade, regulatory frameworks, and the challenges shaping textile industry competitiveness.",

            descriptionId:
                "Berinteraksi dengan stakeholder pemerintah dan industri sekaligus memperluas pengalaman dalam kebijakan industri, perdagangan internasional, regulasi, serta berbagai tantangan yang membentuk daya saing industri tekstil.",
        },

        {
            year: "2011 - Present",
            title: "Industry Knowledge & Business Connectivity",
            titleId: "Pengetahuan Industri & Konektivitas Bisnis",

            descriptionEn:
                "Developing industry publications, business directories, networking initiatives, seminars, business matching activities, and market connectivity programs that connect companies, decision makers, and industry stakeholders.",

            descriptionId:
                "Mengembangkan publikasi industri, direktori bisnis, networking initiatives, seminar, kegiatan business matching, dan program market connectivity yang menghubungkan perusahaan, pengambil keputusan, dan stakeholder industri.",
        },

        {
            year: "2022 - 2025",
            title: "Digital Industry Data & Visibility Foundation",
            titleId: "Fondasi Data Digital & Visibilitas Industri",

            descriptionEn:
                "Expanding company data, industry directory capabilities, and digital visibility initiatives while laying the foundation for structured company information, verification, discoverability, and intelligent industry connectivity.",

            descriptionId:
                "Memperluas data perusahaan, kapabilitas direktori industri, dan inisiatif visibilitas digital sekaligus membangun fondasi untuk informasi perusahaan yang terstruktur, verifikasi, discoverability, dan konektivitas industri yang lebih cerdas.",
        },

        {
            year: "2026",
            title: "DIGESTEX Readable-AI Profile & Visibility Program",
            titleId: "DIGESTEX Readable-AI Profile & Visibility Program",

            descriptionEn:
                "Transforming more than two decades of industry experience, knowledge, networks, company data, and business connectivity into structured Readable-AI Profiles — helping companies become easier to discover, understand, trust, and connect with relevant business opportunities within the DIGESTEX Global Textile Intelligence Ecosystem.",

            descriptionId:
                "Mentransformasikan lebih dari dua dekade pengalaman industri, pengetahuan, jaringan, data perusahaan, dan konektivitas bisnis menjadi Readable-AI Profile yang terstruktur — membantu perusahaan lebih mudah ditemukan, dipahami, dipercaya, dan terhubung dengan peluang bisnis yang relevan dalam DIGESTEX Global Textile Intelligence Ecosystem.",
        },
    ];
    const pillars = [
        "Manufacturers & Brands",
        "Raw Materials & Chemicals",
        "Technology & Machinery",
        "Testing & Certification",
        "Logistics & Supply Chain",
        "Trade Finance & Insurance",
        "Research & Education",
        "Industry Solutions",
    ];

    const solutions = [
        {
            titleEn: "Readable-AI Company Profiles",
            titleId: "Readable-AI Company Profiles",

            descEn: "Transforming company capabilities, products, technologies, certifications, markets, and business information into structured Readable-AI Profiles that are easier to discover, understand, and evaluate.",

            descId: "Mentransformasikan kapabilitas perusahaan, produk, teknologi, sertifikasi, pasar, dan informasi bisnis menjadi Readable-AI Profile yang terstruktur sehingga lebih mudah ditemukan, dipahami, dan dievaluasi.",

            icon: "🏭",
        },

        {
            titleEn: "Digital Visibility & Discoverability",
            titleId: "Visibilitas & Discoverability Digital",

            descEn: "Helping companies strengthen their digital presence and become more discoverable to buyers, sourcing teams, investors, brands, and strategic business partners.",

            descId: "Membantu perusahaan memperkuat kehadiran digital dan menjadi lebih mudah ditemukan oleh buyer, tim sourcing, investor, brand, dan mitra bisnis strategis.",

            icon: "🔎",
        },

        {
            titleEn: "Sourcing & Business Matching",
            titleId: "Sourcing & Business Matching",

            descEn: "Connecting buyers, suppliers, and manufacturers through RFQs, supplier discovery, Smart Business Matching™, sourcing opportunities, and direct business connections.",

            descId: "Menghubungkan buyer, supplier, dan manufacturer melalui RFQ, supplier discovery, Smart Business Matching™, peluang sourcing, dan koneksi bisnis secara langsung.",

            icon: "🤝",
        },

        {
            titleEn: "Collective Sourcing & MOQ Matching",
            titleId: "Collective Sourcing & MOQ Matching",

            descEn: "Helping companies combine demand, overcome MOQ barriers, and access materials and products through collaborative sourcing and intelligent demand matching.",

            descId: "Membantu perusahaan menggabungkan kebutuhan, mengatasi kendala MOQ, serta memperoleh material dan produk melalui collaborative sourcing dan intelligent demand matching.",

            icon: "📦",
        },

        {
            titleEn: "Industry & Trade Intelligence",
            titleId: "Industry & Trade Intelligence",

            descEn: "Transforming trade data, HS-level analytics, market movements, pricing signals, and industry information into intelligence that supports better commercial and strategic decisions.",

            descId: "Mentransformasikan data perdagangan, analitik tingkat HS, pergerakan pasar, sinyal harga, dan informasi industri menjadi intelligence untuk mendukung keputusan komersial dan strategis yang lebih baik.",

            icon: "📊",
        },

        {
            titleEn: "Technology & Solution Ecosystem",
            titleId: "Ekosistem Teknologi & Solusi",

            descEn: "Connecting textile companies with relevant technologies, machinery, digital solutions, testing and certification, technical expertise, and specialized industry service providers.",

            descId: "Menghubungkan perusahaan tekstil dengan teknologi, mesin, solusi digital, testing dan certification, keahlian teknis, serta penyedia layanan industri yang relevan.",

            icon: "⚙️",
        },

        {
            titleEn: "Executive & AI Intelligence",
            titleId: "Executive & AI Intelligence",

            descEn: "Enabling executives and decision makers to access deeper market, trade, company, and industry intelligence through evolving AI-powered intelligence services.",

            descId: "Membantu eksekutif dan pengambil keputusan memperoleh market, trade, company, dan industry intelligence yang lebih mendalam melalui layanan intelligence berbasis AI yang terus berkembang.",

            icon: "🧠",
        },

        {
            titleEn: "Global Business Connectivity",
            titleId: "Konektivitas Bisnis Global",

            descEn: "Creating a connected business environment where companies, capabilities, technologies, intelligence, buyers, suppliers, and strategic partners can discover and connect with relevant opportunities.",

            descId: "Membangun lingkungan bisnis yang terhubung di mana perusahaan, kapabilitas, teknologi, intelligence, buyer, supplier, dan strategic partner dapat menemukan serta terhubung dengan peluang yang relevan.",

            icon: "🌎",
        },
    ];

    return (
        <WebsiteLayout>
            {/* HERO */}

            {/* ==========================================================
    HERO — ABOUT DIGESTEX
========================================================== */}

            <section className="py-24">
                <div className="max-w-6xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        {isEn ? "ABOUT DIGESTEX" : "TENTANG DIGESTEX"}
                    </span>

                    <h1
                        className="
                text-5xl
                md:text-7xl
                font-black
                text-white
                mt-6
                leading-[0.95]
                uppercase
            "
                    >
                        {isEn ? (
                            <>
                                The One-Stop
                                <br />
                                Textile Industry Ecosystem
                            </>
                        ) : (
                            <>
                                The One-Stop
                                <br />
                                Textile Industry Ecosystem
                            </>
                        )}
                    </h1>

                    <p
                        className="
                mt-8
                text-white
                text-xl
                md:text-2xl
                font-bold
                max-w-5xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "Connecting the textile industry from upstream to downstream — together with the technologies, solutions, services, intelligence, and supporting industries that keep the industry moving."
                            : "Menghubungkan industri tekstil dari hulu hingga hilir — bersama teknologi, solusi, layanan, intelligence, dan industri pendukung yang menjaga industri terus bergerak."}
                    </p>

                    <p
                        className="
                mt-6
                text-yellow-400
                text-sm
                md:text-base
                font-black
                uppercase
                tracking-[0.2em]
            "
                    >
                        {isEn
                            ? "Built in Indonesia. Connected to the Global Textile Industry."
                            : "Dibangun di Indonesia. Terhubung dengan Industri Tekstil Global."}
                    </p>

                    <p
                        className="
                mt-10
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "DIGESTEX is an independent digital industry platform built from more than two decades of experience across the textile sector — including manufacturers, suppliers, buyers, technology providers, industry associations, government stakeholders, business networks, and international textile media."
                            : "DIGESTEX adalah platform digital industri independen yang dibangun dari pengalaman lebih dari dua dekade di sektor tekstil — mencakup manufacturer, supplier, buyer, technology provider, asosiasi industri, stakeholder pemerintah, jaringan bisnis, dan media tekstil internasional."}
                    </p>

                    <p
                        className="
                mt-6
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "DIGESTEX is being developed as a connected digital environment where companies across the textile value chain can discover relevant industry information, technologies, solutions, services, suppliers, buyers, partners, markets, and business opportunities within one ecosystem."
                            : "DIGESTEX dikembangkan sebagai lingkungan digital yang terhubung di mana perusahaan di seluruh rantai nilai industri tekstil dapat menemukan informasi industri, teknologi, solusi, layanan, supplier, buyer, partner, pasar, dan peluang bisnis yang relevan dalam satu ekosistem."}
                    </p>

                    <p
                        className="
                mt-6
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "From raw materials and manufacturing to technology, machinery, testing and certification, logistics, trade finance, research, education, exhibitions, and other supporting industries, DIGESTEX brings the industry together through connected digital programs and solutions."
                            : "Mulai dari raw materials dan manufacturing hingga teknologi, machinery, testing dan certification, logistics, trade finance, research, education, exhibitions, serta berbagai industri pendukung lainnya, DIGESTEX mempertemukan industri melalui berbagai program dan solusi digital yang saling terhubung."}
                    </p>

                    <p
                        className="
                mt-6
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "These include company intelligence, digital company profiles, Readable-AI Profiles, trade intelligence, sourcing, business matching, technology and solution discovery, and other initiatives designed to strengthen industry connectivity."
                            : "Program-program tersebut mencakup company intelligence, digital company profiles, Readable-AI Profiles, trade intelligence, sourcing, business matching, technology dan solution discovery, serta berbagai inisiatif lainnya untuk memperkuat konektivitas industri."}
                    </p>

                    <p
                        className="
                mt-6
                text-slate-400
                text-lg
                max-w-4xl
                mx-auto
                leading-relaxed
            "
                    >
                        {isEn
                            ? "Starting from Indonesia, DIGESTEX is designed to progressively connect with major textile and apparel markets around the world — creating a more visible, connected, intelligent, and accessible textile industry ecosystem."
                            : "Dimulai dari Indonesia, DIGESTEX dirancang untuk secara bertahap terhubung dengan berbagai pasar utama tekstil dan apparel di seluruh dunia — menciptakan ekosistem industri tekstil yang lebih visible, terhubung, intelligent, dan mudah diakses."}
                    </p>

                    <div className="flex flex-wrap justify-center gap-3 mt-12">
                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            {isEn
                                ? "20+ Years Industry Experience"
                                : "20+ Tahun Pengalaman Industri"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            {isEn
                                ? "One-Stop Textile Industry Ecosystem"
                                : "One-Stop Textile Industry Ecosystem"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            {isEn
                                ? "Upstream to Downstream"
                                : "Hulu hingga Hilir"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            {isEn
                                ? "Industry & Trade Intelligence"
                                : "Industry & Trade Intelligence"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs text-slate-300">
                            {isEn
                                ? "Global Business Connectivity"
                                : "Konektivitas Bisnis Global"}
                        </span>
                    </div>
                </div>
            </section>

            <section className="py-24 border-t border-white/5">
                <div className="max-w-6xl mx-auto px-6">
                    <div className="text-center mb-16">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            {isEn ? "OUR JOURNEY" : "PERJALANAN KAMI"}
                        </span>

                        <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                            {isEn
                                ? "More Than Two Decades of Industry Experience"
                                : "Lebih Dari Dua Dekade Pengalaman Industri"}
                        </h2>

                        <p className="text-gray-400 text-lg max-w-3xl mx-auto mt-6 leading-relaxed">
                            {isEn
                                ? "DIGESTEX is built upon more than two decades of experience across the textile industry, business networks, industry institutions, and international textile markets."
                                : "DIGESTEX dibangun berdasarkan pengalaman lebih dari dua dekade di industri tekstil, jaringan bisnis, institusi industri, dan pasar tekstil internasional."}
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 gap-6">
                        {timeline.map((item) => (
                            <div
                                key={item.year}
                                className="
                        rounded-[32px]
                        border border-white/10
                        bg-white/5
                        p-8
                        transition
                        hover:border-yellow-500/30
                        hover:bg-white/[0.07]
                    "
                            >
                                <div className="text-yellow-500 font-black text-xl">
                                    {item.year}
                                </div>

                                <h3 className="text-white text-2xl font-black mt-2">
                                    {item.title}
                                </h3>

                                <p className="text-gray-400 mt-4 leading-relaxed">
                                    {item.description}
                                </p>
                            </div>
                        ))}
                    </div>

                    <div className="mt-14 max-w-4xl mx-auto text-center">
                        <p className="text-lg md:text-xl text-slate-300 leading-relaxed">
                            {isEn
                                ? "These experiences provide the industry understanding, relationships, and perspective that form the foundation of DIGESTEX — transforming real-world industry knowledge into a connected digital ecosystem."
                                : "Pengalaman tersebut menjadi fondasi pemahaman industri, hubungan, dan perspektif yang membentuk DIGESTEX — mentransformasikan pengalaman nyata industri menjadi ekosistem digital yang terhubung."}
                        </p>
                    </div>
                </div>
            </section>
            {/* BUILT FROM INDUSTRY EXPERIENCE */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        {isEn
                            ? "BUILT FROM REAL INDUSTRY EXPERIENCE"
                            : "DIBANGUN DARI PENGALAMAN INDUSTRI NYATA"}
                    </span>

                    <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                        {isEn
                            ? "Transforming Industry Experience Into Digital Infrastructure"
                            : "Mentransformasikan Pengalaman Industri Menjadi Infrastruktur Digital"}
                    </h2>

                    <p className="mt-8 text-gray-400 text-lg leading-relaxed">
                        {isEn
                            ? "DIGESTEX is built on more than two decades of direct experience across the textile industry — connecting manufacturers, suppliers, buyers, technology providers, industry institutions, trade networks, government stakeholders, and international textile media."
                            : "DIGESTEX dibangun berdasarkan lebih dari dua dekade pengalaman langsung di industri tekstil — menghubungkan manufacturer, supplier, buyer, technology provider, institusi industri, jaringan perdagangan, stakeholder pemerintah, dan media tekstil internasional."}
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        {isEn
                            ? "This experience provides a practical understanding of how the textile value chain operates, how companies build and evaluate capabilities, how technologies and solutions are adopted, and how business relationships and opportunities develop across the industry."
                            : "Pengalaman tersebut memberikan pemahaman praktis mengenai bagaimana rantai nilai industri tekstil berjalan, bagaimana perusahaan membangun dan mengevaluasi kapabilitas, bagaimana teknologi dan solusi diadopsi, serta bagaimana hubungan dan peluang bisnis berkembang di dalam industri."}
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        {isEn
                            ? "DIGESTEX translates this industry knowledge into a connected digital infrastructure that brings together companies, industry intelligence, technologies, solutions, services, sourcing, markets, and business opportunities across the textile value chain."
                            : "DIGESTEX mentransformasikan pengetahuan industri tersebut menjadi infrastruktur digital yang terhubung untuk mempertemukan perusahaan, industry intelligence, teknologi, solusi, layanan, sourcing, pasar, dan peluang bisnis di seluruh rantai nilai industri tekstil."}
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        {isEn
                            ? "Digital capabilities such as structured company intelligence and Readable-AI Profiles are part of this broader infrastructure — helping companies become more visible, understandable, discoverable, and connected within the evolving digital and AI-driven business environment."
                            : "Kapabilitas digital seperti structured company intelligence dan Readable-AI Profiles merupakan bagian dari infrastruktur yang lebih luas tersebut — membantu perusahaan menjadi lebih visible, mudah dipahami, mudah ditemukan, dan lebih terhubung dalam lingkungan bisnis digital dan berbasis AI yang terus berkembang."}
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        {isEn
                            ? "The broader objective is to create a more connected textile industry ecosystem where industry participants can discover relevant information, capabilities, technologies, solutions, partners, markets, and opportunities through one integrated platform."
                            : "Tujuan yang lebih luas adalah menciptakan ekosistem industri tekstil yang lebih terhubung, di mana pelaku industri dapat menemukan informasi, kapabilitas, teknologi, solusi, partner, pasar, dan peluang yang relevan melalui satu platform yang terintegrasi."}
                    </p>
                </div>
            </section>
            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center max-w-5xl mx-auto mb-20">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            {isEn
                                ? "WHY DIGESTEX EXISTS"
                                : "MENGAPA DIGESTEX HADIR"}
                        </span>

                        <h2 className="text-4xl md:text-6xl font-black text-white mt-6 uppercase leading-tight">
                            {isEn ? (
                                <>
                                    The Textile Industry Is Connected.
                                    <br />
                                    Its Digital Ecosystem Should Be Too.
                                </>
                            ) : (
                                <>
                                    Industri Tekstil Saling Terhubung.
                                    <br />
                                    Ekosistem Digitalnya Juga Harus Terhubung.
                                </>
                            )}
                        </h2>

                        <p className="mt-8 text-gray-400 text-lg leading-relaxed">
                            {isEn
                                ? "The textile industry is a complex global value chain involving manufacturers, suppliers, buyers, technology providers, service companies, financial institutions, logistics providers, testing and certification companies, research institutions, exhibitions, media, and many other supporting industries. Yet much of this ecosystem remains fragmented across different platforms, networks, sources of information, and business channels."
                                : "Industri tekstil merupakan rantai nilai global yang kompleks, melibatkan manufacturer, supplier, buyer, technology provider, perusahaan jasa, institusi keuangan, penyedia logistik, perusahaan testing dan certification, lembaga riset, pameran, media, serta berbagai supporting industry lainnya. Namun sebagian besar ekosistem tersebut masih tersebar di berbagai platform, jaringan, sumber informasi, dan kanal bisnis yang berbeda."}
                        </p>

                        <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                            {isEn
                                ? "DIGESTEX was created to bring these elements together through one connected digital infrastructure — creating a One-Stop Textile Industry Ecosystem that connects the industry from upstream to downstream together with the technologies, solutions, services, intelligence, and supporting industries that keep the industry moving."
                                : "DIGESTEX hadir untuk mempertemukan berbagai elemen tersebut melalui satu infrastruktur digital yang terhubung — membangun One-Stop Textile Industry Ecosystem yang menghubungkan industri dari hulu hingga hilir, bersama teknologi, solusi, layanan, intelligence, dan supporting industry yang membuat industri terus bergerak."}
                        </p>

                        <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                            {isEn
                                ? "Within this ecosystem, DIGESTEX connects industry intelligence, companies, sourcing, solutions, markets, business opportunities, and professional networks — helping companies and industry stakeholders discover relevant information, capabilities, technologies, partners, and opportunities more efficiently."
                                : "Di dalam ekosistem ini, DIGESTEX menghubungkan industry intelligence, perusahaan, sourcing, solusi, pasar, peluang bisnis, dan jaringan profesional — membantu perusahaan dan stakeholder industri menemukan informasi, kapabilitas, teknologi, mitra, dan peluang yang relevan secara lebih efisien."}
                        </p>

                        <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                            {isEn
                                ? "Readable-AI Profiles are one important part of this infrastructure — helping companies structure their capabilities so they can be discovered and understood by people, search systems, and AI. But the larger objective is to create a connected digital environment for the entire textile industry ecosystem."
                                : "Readable-AI Profile merupakan salah satu bagian penting dari infrastruktur ini — membantu perusahaan menstrukturkan kapabilitasnya agar dapat ditemukan dan dipahami oleh manusia, sistem pencarian, dan AI. Namun tujuan yang lebih besar adalah membangun lingkungan digital yang terhubung bagi seluruh ekosistem industri tekstil."}
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {/* One-Stop Ecosystem */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">🌐</div>

                            <h3 className="text-white text-xl font-black">
                                {isEn
                                    ? "One-Stop Ecosystem"
                                    : "One-Stop Ecosystem"}
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                {isEn
                                    ? "Bring industry companies, technologies, solutions, services, intelligence and supporting industries together within one connected platform."
                                    : "Mempertemukan perusahaan industri, teknologi, solusi, layanan, intelligence dan supporting industry dalam satu platform yang terhubung."}
                            </p>
                        </div>

                        {/* Value Chain */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">🔗</div>

                            <h3 className="text-white text-xl font-black">
                                {isEn
                                    ? "Upstream To Downstream"
                                    : "Hulu Hingga Hilir"}
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                {isEn
                                    ? "Connect the textile value chain from raw materials and manufacturing to sourcing, markets, brands, buyers and supporting industries."
                                    : "Menghubungkan rantai nilai tekstil mulai dari bahan baku dan manufaktur hingga sourcing, pasar, brand, buyer dan supporting industry."}
                            </p>
                        </div>

                        {/* Intelligence */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">🧠</div>

                            <h3 className="text-white text-xl font-black">
                                {isEn
                                    ? "Industry Intelligence"
                                    : "Industry Intelligence"}
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                {isEn
                                    ? "Make industry, trade, company, market and executive intelligence more accessible for better business decisions."
                                    : "Membuat intelligence industri, trade, perusahaan, pasar dan executive lebih mudah diakses untuk mendukung keputusan bisnis yang lebih baik."}
                            </p>
                        </div>

                        {/* Connectivity */}
                        <div className="rounded-[32px] border border-white/10 bg-white/5 p-8 hover:bg-white/[0.07] transition-all">
                            <div className="text-4xl mb-5">🤝</div>

                            <h3 className="text-white text-xl font-black">
                                {isEn
                                    ? "Business Connectivity"
                                    : "Konektivitas Bisnis"}
                            </h3>

                            <p className="text-gray-400 mt-4 leading-relaxed">
                                {isEn
                                    ? "Create better pathways between companies, buyers, suppliers, technologies, solutions, partners and global business opportunities."
                                    : "Menciptakan jalur yang lebih baik antara perusahaan, buyer, supplier, teknologi, solusi, mitra dan peluang bisnis global."}
                            </p>
                        </div>
                    </div>

                    <div className="mt-16 max-w-5xl mx-auto text-center">
                        <p className="text-xl text-slate-300 leading-relaxed">
                            {isEn
                                ? "DIGESTEX is designed as the digital infrastructure connecting the textile industry ecosystem — from upstream to downstream, from intelligence to business connectivity, and from Indonesia toward the global textile industry."
                                : "DIGESTEX dirancang sebagai infrastruktur digital yang menghubungkan ekosistem industri tekstil — dari hulu hingga hilir, dari intelligence hingga konektivitas bisnis, dan dari Indonesia menuju industri tekstil global."}
                        </p>

                        <p className="mt-8 text-2xl font-black text-white leading-relaxed">
                            {isEn
                                ? "The goal is simple: make the textile industry more connected, more intelligent, more discoverable, and more accessible through one ecosystem."
                                : "Tujuannya sederhana: membuat industri tekstil semakin terhubung, semakin intelligent, semakin mudah ditemukan, dan semakin mudah diakses melalui satu ekosistem."}
                        </p>
                    </div>
                </div>
            </section>

            <section className="py-24 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        {isEn ? "OUR MISSION" : "MISI KAMI"}
                    </span>

                    <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase leading-tight">
                        {isEn ? (
                            <>
                                Connecting the Global Textile Industry
                                <br />
                                From Upstream to Downstream
                            </>
                        ) : (
                            <>
                                Menghubungkan Industri Tekstil Global
                                <br />
                                Dari Hulu sampai Hilir
                            </>
                        )}
                    </h2>

                    <p className="mt-8 text-gray-400 text-lg leading-relaxed">
                        {isEn
                            ? "DIGESTEX's mission is to build a connected, intelligent, and trusted digital ecosystem for the global textile industry — bringing together companies, raw materials, manufacturers, technologies, solutions, suppliers, buyers, markets, trade intelligence, and business opportunities within one integrated platform."
                            : "Misi DIGESTEX adalah membangun ekosistem digital industri tekstil global yang terhubung, intelligent, dan terpercaya — mempertemukan perusahaan, bahan baku, manufacturer, teknologi, solusi, supplier, buyer, pasar, trade intelligence, dan peluang bisnis dalam satu platform yang terintegrasi."}
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        {isEn
                            ? "From upstream materials and manufacturing to downstream products, sourcing, trade, technology, services, and supporting industries, DIGESTEX is designed to connect the different capabilities and needs of the textile value chain in one digital environment."
                            : "Mulai dari bahan baku dan manufaktur di sisi hulu hingga produk, sourcing, perdagangan, teknologi, jasa, dan supporting industries di sisi hilir, DIGESTEX dirancang untuk menghubungkan berbagai kapabilitas dan kebutuhan dalam rantai nilai industri tekstil melalui satu lingkungan digital."}
                    </p>

                    <p className="mt-6 text-gray-400 text-lg leading-relaxed">
                        {isEn
                            ? "Through specialized programs and intelligence services, DIGESTEX helps companies become more visible, enables better discovery and connectivity, provides relevant market and trade intelligence, and creates pathways toward sourcing, collaboration, investment, and new business opportunities."
                            : "Melalui berbagai program dan layanan intelligence yang terintegrasi, DIGESTEX membantu perusahaan meningkatkan visibility, memudahkan discovery dan konektivitas, menyediakan market dan trade intelligence yang relevan, serta membuka jalur menuju sourcing, kolaborasi, investasi, dan peluang bisnis baru."}
                    </p>

                    <p className="mt-8 text-2xl font-black text-white leading-relaxed">
                        {isEn
                            ? "One Industry. One Connected Ecosystem. One Digital Platform."
                            : "Satu Industri. Satu Ekosistem yang Terhubung. Satu Platform Digital."}
                    </p>
                </div>
            </section>
            {/* DIGESTEX TODAY */}

            <section className="py-24 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-6">
                    <div className="text-center mb-16">
                        <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                            {isEn ? "DIGESTEX TODAY" : "DIGESTEX HARI INI"}
                        </span>

                        <h2 className="text-4xl md:text-5xl font-black text-white mt-4 uppercase">
                            {isEn
                                ? "The One-Stop Textile Industry Ecosystem"
                                : "One-Stop Textile Industry Ecosystem"}
                        </h2>

                        <p className="max-w-5xl mx-auto text-lg text-slate-400 leading-relaxed mt-8">
                            {isEn
                                ? "DIGESTEX is building a connected digital ecosystem for the textile industry — bringing together companies, industry intelligence, sourcing, technologies, solutions, services, markets, business opportunities, and supporting industries across the textile value chain."
                                : "DIGESTEX membangun ekosistem digital yang terhubung untuk industri tekstil — mempertemukan perusahaan, industry intelligence, sourcing, teknologi, solusi, layanan, pasar, peluang bisnis, dan supporting industry di seluruh rantai nilai industri tekstil."}
                        </p>

                        <p className="max-w-4xl mx-auto text-lg text-slate-400 leading-relaxed mt-6">
                            {isEn
                                ? "Built in Indonesia, DIGESTEX is designed to progressively connect the Indonesian textile industry with regional and global markets — creating a more connected, intelligent, discoverable, and accessible industry ecosystem."
                                : "Dibangun di Indonesia, DIGESTEX dirancang untuk secara bertahap menghubungkan industri tekstil Indonesia dengan pasar regional dan global — menciptakan ekosistem industri yang lebih terhubung, lebih intelligent, lebih mudah ditemukan, dan lebih mudah diakses."}
                        </p>
                    </div>

                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {solutions.map((item) => (
                            <div
                                key={item.title}
                                className="
                        group
                        rounded-[32px]
                        border
                        border-white/10
                        bg-gradient-to-b
                        from-white/5
                        to-white/[0.02]
                        p-8
                        hover:border-yellow-500/30
                        hover:bg-white/[0.07]
                        transition-all
                        duration-300
                    "
                            >
                                <div className="text-4xl mb-5">{item.icon}</div>

                                <h3 className="text-white text-xl font-black">
                                    {isEn ? item.titleEn : item.titleId}
                                </h3>

                                <p className="mt-4 text-gray-400 leading-relaxed">
                                    {isEn ? item.descEn : item.descId}
                                </p>
                            </div>
                        ))}
                    </div>

                    <div className="mt-16 text-center">
                        <p className="text-xl md:text-2xl font-black text-white leading-relaxed">
                            {isEn
                                ? "From upstream to downstream. From intelligence to opportunity. From Indonesia to the global textile industry."
                                : "Dari hulu sampai hilir. Dari intelligence hingga peluang. Dari Indonesia menuju industri tekstil global."}
                        </p>
                    </div>
                </div>
            </section>

            {/* CTA */}

            <section className="py-32 border-t border-white/5">
                <div className="max-w-5xl mx-auto px-6 text-center">
                    <span className="text-yellow-500 text-xs font-black uppercase tracking-[0.4em]">
                        {isEn
                            ? "CONNECTING THE TEXTILE INDUSTRY ECOSYSTEM"
                            : "MENGHUBUNGKAN EKOSISTEM INDUSTRI TEKSTIL"}
                    </span>

                    <h2 className="text-4xl md:text-6xl font-black text-white mt-6 uppercase leading-tight">
                        {isEn ? (
                            <>
                                Connect With The Future Of
                                <br />
                                The Textile Industry
                            </>
                        ) : (
                            <>
                                Terhubung Dengan Masa Depan
                                <br />
                                Industri Tekstil
                            </>
                        )}
                    </h2>

                    <p className="mt-8 text-slate-400 text-lg leading-relaxed max-w-4xl mx-auto">
                        {isEn
                            ? "DIGESTEX is creating a One-Stop Textile Industry Ecosystem that connects the textile value chain from upstream to downstream — together with the technologies, solutions, services, intelligence, and supporting industries that keep the industry moving."
                            : "DIGESTEX membangun One-Stop Textile Industry Ecosystem yang menghubungkan rantai nilai industri tekstil dari hulu hingga hilir — bersama teknologi, solusi, layanan, intelligence, dan supporting industry yang membuat industri terus bergerak."}
                    </p>

                    <p className="mt-8 text-slate-300 text-lg leading-relaxed max-w-3xl mx-auto">
                        {isEn
                            ? "Starting from Indonesia and progressively connecting to the global textile industry, DIGESTEX is opening selected opportunities for companies, technology providers, institutions, strategic partners, sponsors, and ecosystem investors to participate in the development and growth of the ecosystem."
                            : "Dimulai dari Indonesia dan secara bertahap terhubung dengan industri tekstil global, DIGESTEX membuka peluang terpilih bagi perusahaan, technology provider, institusi, strategic partner, sponsor, dan ecosystem investor untuk berpartisipasi dalam pengembangan dan pertumbuhan ekosistem."}
                    </p>

                    <div className="flex flex-wrap justify-center gap-3 mt-10">
                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            🌍{" "}
                            {isEn
                                ? "Global Industry Visibility"
                                : "Visibility Industri Global"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            ⚙️{" "}
                            {isEn
                                ? "Technology & Solution Connectivity"
                                : "Konektivitas Teknologi & Solusi"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            🤝{" "}
                            {isEn
                                ? "Strategic Ecosystem Positioning"
                                : "Posisi Strategis Dalam Ekosistem"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            📊{" "}
                            {isEn
                                ? "Industry Intelligence"
                                : "Industry Intelligence"}
                        </span>

                        <span className="px-4 py-2 rounded-full bg-white/5 border border-white/10 text-xs font-semibold text-slate-300">
                            🚀{" "}
                            {isEn
                                ? "Business & Market Opportunities"
                                : "Peluang Bisnis & Pasar"}
                        </span>
                    </div>

                    <div className="mt-16 grid md:grid-cols-3 gap-6 text-left">
                        {/* Solution Partner */}

                        <div className="rounded-[28px] border border-white/10 bg-white/5 p-6">
                            <h3 className="text-white font-black text-lg">
                                {isEn ? "Solution Partner" : "Solution Partner"}
                            </h3>

                            <p className="text-slate-400 mt-3 text-sm leading-relaxed">
                                {isEn
                                    ? "Bring your technologies, machinery, services, expertise, and solutions into a connected environment where relevant textile companies and decision makers can discover and evaluate them."
                                    : "Membawa teknologi, mesin, layanan, keahlian, dan solusi perusahaan ke dalam lingkungan yang terhubung sehingga dapat ditemukan dan dievaluasi oleh perusahaan tekstil serta decision maker yang relevan."}
                            </p>
                        </div>

                        {/* Strategic Partner */}

                        <div className="rounded-[28px] border border-white/10 bg-white/5 p-6">
                            <h3 className="text-white font-black text-lg">
                                {isEn
                                    ? "Strategic Partner"
                                    : "Strategic Partner"}
                            </h3>

                            <p className="text-slate-400 mt-3 text-sm leading-relaxed">
                                {isEn
                                    ? "Work with DIGESTEX to develop industry programs, intelligence, sourcing, connectivity, events, knowledge initiatives, and other strategic ecosystem capabilities."
                                    : "Berkolaborasi dengan DIGESTEX dalam pengembangan program industri, intelligence, sourcing, konektivitas, event, knowledge initiatives, dan berbagai kapabilitas strategis ekosistem lainnya."}
                            </p>
                        </div>

                        {/* Sponsor / Ecosystem Investor */}

                        <div className="rounded-[28px] border border-white/10 bg-white/5 p-6">
                            <h3 className="text-white font-black text-lg">
                                {isEn
                                    ? "Sponsor & Ecosystem Investor"
                                    : "Sponsor & Ecosystem Investor"}
                            </h3>

                            <p className="text-slate-400 mt-3 text-sm leading-relaxed">
                                {isEn
                                    ? "Support the development, expansion, and long-term growth of a connected digital infrastructure for the textile industry — from Indonesia toward the global market."
                                    : "Mendukung pengembangan, ekspansi, dan pertumbuhan jangka panjang infrastruktur digital yang terhubung untuk industri tekstil — dari Indonesia menuju pasar global."}
                            </p>
                        </div>
                    </div>

                    <div className="mt-16">
                        <Link
                            href={route("ecosystem-partner.index")}
                            className="
                    inline-flex
                    items-center
                    justify-center
                    px-10
                    py-5
                    rounded-full
                    bg-yellow-500
                    hover:bg-yellow-400
                    text-black
                    font-black
                    uppercase
                    text-xs
                    tracking-[0.25em]
                    transition-all
                    duration-300
                    shadow-xl
                "
                        >
                            {isEn
                                ? "Explore Ecosystem Partnership Opportunities"
                                : "Lihat Peluang Kemitraan Ekosistem"}
                        </Link>

                        <p className="mt-6 text-xs uppercase tracking-[0.3em] text-slate-500">
                            {isEn
                                ? "Selected Opportunities Available Across Ecosystem Categories"
                                : "Peluang Terpilih Tersedia di Berbagai Kategori Ekosistem"}
                        </p>
                    </div>
                </div>
            </section>
        </WebsiteLayout>
    );
}
