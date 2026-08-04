/*
|--------------------------------------------------------------------------
| Capability Intelligence™
|--------------------------------------------------------------------------
|
| Generates the intelligence panel shown on the onboarding
| page based on the company's primary business category.
|
*/

export function getCapabilityIntelligence(
    category = "manufacturer",
    isEn = true,
) {
    const intelligence = {
        manufacturer: {
            title: "Manufacturer Intelligence™",

            description: isEn
                ? "Provide manufacturing capability information to help DIGESTEX understand your production capacity, operational strengths, and factory readiness."
                : "Lengkapi informasi kapabilitas manufaktur untuk membantu DIGESTEX memahami kapasitas produksi, keunggulan operasional, dan kesiapan pabrik perusahaan.",

            items: [
                "Capacity Intelligence™",
                "Production Intelligence™",
                "Commercial Intelligence™",
                "Factory Intelligence™",
                "Executive Dashboard™",
                "Smart Business Matching™",
            ],
        },

        quality_infrastructure: {
            title: "Quality Intelligence™",

            description: isEn
                ? "Provide quality infrastructure information including laboratory services, accreditation, testing, inspection, and certification capabilities."
                : "Lengkapi informasi quality infrastructure meliputi layanan laboratorium, akreditasi, pengujian, inspeksi, dan sertifikasi.",

            items: [
                "Laboratory Intelligence™",
                "Testing Intelligence™",
                "Inspection Intelligence™",
                "Certification Intelligence™",
                "Executive Dashboard™",
                "Company Intelligence™",
            ],
        },

        supporting_industry: {
            title: "Supporting Industry Intelligence™",

            description: isEn
                ? "Describe your products, technical expertise, industrial solutions, and distribution capabilities to improve visibility within the textile ecosystem."
                : "Jelaskan produk, keahlian teknis, solusi industri, dan kemampuan distribusi untuk meningkatkan visibilitas dalam ekosistem tekstil.",

            items: [
                "Product Intelligence™",
                "Technical Support™",
                "Distribution Intelligence™",
                "Industrial Solutions™",
                "Company Intelligence™",
                "Smart Business Matching™",
            ],
        },

        commercial: {
            title: "Commercial Intelligence™",

            description: isEn
                ? "Provide information about your buyer network, sourcing expertise, market coverage, and international trade capabilities."
                : "Lengkapi informasi mengenai jaringan buyer, kemampuan sourcing, cakupan pasar, dan perdagangan internasional.",

            items: [
                "Buyer Intelligence™",
                "Market Intelligence™",
                "Trade Intelligence™",
                "Export Intelligence™",
                "Global Company Directory™",
                "Build My Supply Chain™",
            ],
        },
    };

    return intelligence[category] ?? intelligence.manufacturer;
}
