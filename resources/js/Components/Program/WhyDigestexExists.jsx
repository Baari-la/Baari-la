import { usePage } from "@inertiajs/react";

export default function WhyDigestexExists() {
    const { locale = "en" } = usePage().props;

    const content = {
        en: {
            badge: "FROM PRINTED DIRECTORY TO LIVING DIRECTORY",

            title: "The Industry Moves Fast. Your Company Information Should Too.",

            description:
                "For years, printed directories have played an important role in connecting Indonesia's textile industry. However, maintaining accurate company information has become increasingly challenging.",

            problems: [
                "Companies that had ceased operations",
                "Changes in management and contact information",
                "New companies not yet listed",
                "Changes in production capabilities",
                "Changes in export markets",
                "New certifications and compliance requirements",
            ],

            footer: "DIGESTEX was created to help maintain accurate, visible, and connected company information across Indonesia's textile ecosystem.",

            quote: "DIGESTEX is a Living Directory. Not updated every two years. Updated whenever your business evolves.",

            comparisonTitle: "Traditional Directory vs DIGESTEX",
        },

        id: {
            badge: "DARI DIREKTORI CETAK MENUJU LIVING DIRECTORY",

            title: "Industri Bergerak Cepat. Informasi Perusahaan Anda Juga Harus Bergerak.",

            description:
                "Selama bertahun-tahun, direktori cetak telah berperan penting dalam menghubungkan industri tekstil Indonesia. Namun, menjaga agar informasi perusahaan tetap akurat menjadi tantangan yang semakin besar.",

            problems: [
                "Perusahaan yang sudah tidak beroperasi",
                "Perubahan manajemen dan informasi kontak",
                "Perusahaan baru yang belum tercantum",
                "Perubahan kapasitas produksi",
                "Perubahan pasar ekspor",
                "Sertifikasi dan persyaratan kepatuhan baru",
            ],

            footer: "DIGESTEX dikembangkan untuk membantu menjaga informasi perusahaan agar tetap akurat, terlihat, dan terhubung dalam ekosistem industri tekstil Indonesia.",

            quote: "DIGESTEX adalah Living Directory. Bukan diperbarui setiap dua tahun, tetapi diperbarui setiap kali bisnis Anda berkembang.",

            comparisonTitle: "Direktori Tradisional vs DIGESTEX",
        },
    };

    const t = content[locale];

    return (
        <section className="bg-slate-50 py-24">
            <div className="mx-auto max-w-7xl px-6">
                {/* Badge */}

                <div className="mb-6">
                    <span
                        className="
                            rounded-full
                            bg-blue-100
                            px-4
                            py-2
                            text-sm
                            font-semibold
                            text-blue-700
                        "
                    >
                        {t.badge}
                    </span>
                </div>

                {/* Title */}

                <h2
                    className="
                        max-w-4xl
                        text-4xl
                        font-black
                        leading-tight
                        text-slate-900
                    "
                >
                    {t.title}
                </h2>

                <p
                    className="
                        mt-6
                        max-w-4xl
                        text-lg
                        leading-8
                        text-slate-600
                    "
                >
                    {t.description}
                </p>

                {/* Problems */}

                <div className="mt-12 grid gap-4 md:grid-cols-2">
                    {t.problems.map((item) => (
                        <div
                            key={item}
                            className="
                                rounded-2xl
                                bg-white
                                p-6
                                shadow-sm
                            "
                        >
                            <div className="flex items-start gap-4">
                                <div
                                    className="
                                        flex
                                        h-8
                                        w-8
                                        items-center
                                        justify-center
                                        rounded-full
                                        bg-red-100
                                        font-bold
                                        text-red-600
                                    "
                                >
                                    !
                                </div>

                                <div className="text-slate-700">{item}</div>
                            </div>
                        </div>
                    ))}
                </div>

                {/* Comparison */}

                <div
                    className="
                        mt-16
                        overflow-hidden
                        rounded-3xl
                        bg-white
                        shadow-sm
                    "
                >
                    <div className="border-b p-8">
                        <h3 className="text-2xl font-bold">
                            {t.comparisonTitle}
                        </h3>
                    </div>

                    <table className="min-w-full">
                        <thead className="bg-slate-50">
                            <tr>
                                <th className="px-6 py-4 text-left">
                                    Printed Directory
                                </th>

                                <th className="px-6 py-4 text-left">
                                    DIGESTEX
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr className="border-t">
                                <td className="px-6 py-4">
                                    Updated every 2 years
                                </td>

                                <td className="px-6 py-4">Updated anytime</td>
                            </tr>

                            <tr className="border-t">
                                <td className="px-6 py-4">
                                    Static information
                                </td>

                                <td className="px-6 py-4">
                                    Dynamic information
                                </td>
                            </tr>

                            <tr className="border-t">
                                <td className="px-6 py-4">
                                    Limited visibility
                                </td>

                                <td className="px-6 py-4">
                                    Year-round visibility
                                </td>
                            </tr>

                            <tr className="border-t">
                                <td className="px-6 py-4">No matching</td>

                                <td className="px-6 py-4">Smart Matching</td>
                            </tr>

                            <tr className="border-t">
                                <td className="px-6 py-4">No analytics</td>

                                <td className="px-6 py-4">Visibility Score</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {/* Quote */}

                <div
                    className="
                        mt-16
                        rounded-3xl
                        bg-slate-900
                        p-12
                        text-center
                        text-white
                    "
                >
                    <div
                        className="
                            mx-auto
                            max-w-4xl
                            text-3xl
                            font-black
                            leading-relaxed
                        "
                    >
                        "{t.quote}"
                    </div>

                    <p
                        className="
                            mt-8
                            text-lg
                            text-slate-300
                        "
                    >
                        {t.footer}
                    </p>
                </div>

                {/* Timeline */}

                <div className="mt-20 grid gap-8 md:grid-cols-3">
                    <div className="rounded-3xl bg-white p-8 shadow-sm">
                        <div className="text-4xl font-black text-blue-600">
                            2022
                        </div>

                        <div className="mt-4 text-xl font-bold">
                            Printed Directory
                        </div>

                        <p className="mt-2 text-slate-500">First Edition</p>
                    </div>

                    <div className="rounded-3xl bg-white p-8 shadow-sm">
                        <div className="text-4xl font-black text-blue-600">
                            2024
                        </div>

                        <div className="mt-4 text-xl font-bold">
                            Printed Directory
                        </div>

                        <p className="mt-2 text-slate-500">Second Edition</p>
                    </div>

                    <div className="rounded-3xl bg-white p-8 shadow-sm">
                        <div className="text-4xl font-black text-emerald-600">
                            2026
                        </div>

                        <div className="mt-4 text-xl font-bold">
                            DIGESTEX Digital Directory
                        </div>

                        <p className="mt-2 text-slate-500">Living Directory</p>
                    </div>
                </div>
            </div>
        </section>
    );
}
