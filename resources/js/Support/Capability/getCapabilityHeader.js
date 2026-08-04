/*
|--------------------------------------------------------------------------
| Capability Header
|--------------------------------------------------------------------------
|
| Generates the onboarding page header based on the
| primary business category.
|
*/

export function getCapabilityHeader(category = "manufacturer", isEn = true) {
    const headers = {
        manufacturer: {
            title: isEn
                ? "Manufacturer Capability™"
                : "Kapabilitas Manufaktur™",

            description: isEn
                ? "Build your Manufacturing Capability Profile by showcasing production capacity, manufacturing services, commercial flexibility, and operational strengths."
                : "Bangun Profil Kapabilitas Manufaktur dengan menampilkan kapasitas produksi, layanan manufaktur, fleksibilitas komersial, dan keunggulan operasional perusahaan.",
        },

        quality_infrastructure: {
            title: isEn ? "Quality Capability™" : "Kapabilitas Quality™",

            description: isEn
                ? "Build your Quality Capability Profile by presenting laboratory services, testing, inspection, accreditation, certification, and quality assurance expertise."
                : "Bangun Profil Kapabilitas Quality dengan menampilkan layanan laboratorium, pengujian, inspeksi, akreditasi, sertifikasi, dan keahlian quality assurance.",
        },

        supporting_industry: {
            title: isEn
                ? "Supporting Industry Capability™"
                : "Kapabilitas Industri Pendukung™",

            description: isEn
                ? "Build your Supporting Industry Capability Profile by presenting products, industrial solutions, technical services, and distribution capabilities."
                : "Bangun Profil Kapabilitas Industri Pendukung dengan menampilkan produk, solusi industri, layanan teknis, dan kemampuan distribusi.",
        },

        commercial: {
            title: isEn ? "Commercial Capability™" : "Kapabilitas Komersial™",

            description: isEn
                ? "Build your Commercial Capability Profile by showcasing market coverage, buyer services, sourcing expertise, and international trade capabilities."
                : "Bangun Profil Kapabilitas Komersial dengan menampilkan cakupan pasar, layanan buyer, keahlian sourcing, dan kemampuan perdagangan internasional.",
        },
    };

    return headers[category] ?? headers.manufacturer;
}
