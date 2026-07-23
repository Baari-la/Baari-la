import ProgramNavbar from "@/Components/Program/ProgramNavbar";
import { Link, usePage } from "@inertiajs/react";
import StickyWhatsAppButton from "@/Components/Program/StickyWhatsAppButton";

import { Check, Crown, ShieldCheck, ArrowLeft, Sparkles } from "lucide-react";

export default function Step2PackageSelection() {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const packages = [
        {
            name: "Verified Company",
            price: "Rp 2.500.000",

            icon: ShieldCheck,

            features: [
                "Verified Company Badge",
                "Digital Company Passport™",
                "Product Listing",
                "Contact Information",
            ],
        },

        {
            name: "Visibility Partner",
            price: "Rp 5.000.000",

            icon: Sparkles,

            features: [
                "Everything in Verified",
                "Visibility Score™",
                "Featured Listing",
                "Executive Intelligence™",
            ],
        },

        {
            name: "Executive Partner",
            price: "Rp 10.000.000",

            icon: Crown,

            recommended: true,

            features: [
                "Everything in Visibility",
                "Executive Dashboard™",
                "Smart Business Matching™",
                "Build My Supply Chain™",
                "Executive AI Insight™",
            ],
        },
    ];

    return (
        <div className="min-h-screen bg-slate-50">
            <ProgramNavbar currentStep={2} />

            <main className="mx-auto max-w-7xl p-6">
                <div className="space-y-8">
                    {/* Header */}

                    <div className="text-center">
                        <p className="text-sm font-bold uppercase tracking-[0.2em] text-emerald-600">
                            STEP 2
                        </p>

                        <h1 className="mt-4 text-5xl font-black">
                            {isEn
                                ? "Choose Your Package"
                                : "Pilih Paket Partisipasi"}
                        </h1>

                        <p className="mt-4 text-lg text-slate-500">
                            {isEn
                                ? "Select the package that best fits your company's objectives."
                                : "Pilih paket yang paling sesuai dengan kebutuhan perusahaan Anda."}
                        </p>
                    </div>

                    {/* Package Cards */}

                    <div className="grid gap-8 lg:grid-cols-3">
                        {packages.map((pkg) => {
                            const Icon = pkg.icon;

                            return (
                                <div
                                    key={pkg.name}
                                    className={`
                                        relative
                                        rounded-3xl
                                        bg-white
                                        p-8
                                        shadow-sm
                                        transition
                                        hover:-translate-y-1
                                        hover:shadow-xl

                                        ${
                                            pkg.recommended
                                                ? "border border-emerald-500 ring-2 ring-emerald-500"
                                                : "border border-slate-200"
                                        }
                                    `}
                                >
                                    {pkg.recommended && (
                                        <div
                                            className="
                                                absolute
                                                -top-3
                                                left-1/2
                                                -translate-x-1/2
                                                rounded-full
                                                bg-emerald-500
                                                px-4
                                                py-1
                                                text-xs
                                                font-bold
                                                text-white
                                            "
                                        >
                                            {isEn
                                                ? "RECOMMENDED"
                                                : "REKOMENDASI"}
                                        </div>
                                    )}

                                    <Icon className="h-10 w-10 text-emerald-500" />

                                    <h2 className="mt-6 text-2xl font-black">
                                        {pkg.name}
                                    </h2>

                                    <div className="mt-4">
                                        <span className="text-4xl font-black">
                                            {pkg.price}
                                        </span>

                                        <span className="text-slate-500">
                                            {" "}
                                            / year
                                        </span>
                                    </div>

                                    <div className="mt-8 space-y-4">
                                        {pkg.features.map((feature) => (
                                            <div
                                                key={feature}
                                                className="
                                                    flex
                                                    items-center
                                                    gap-3
                                                "
                                            >
                                                <Check className="h-5 w-5 text-emerald-500" />

                                                {feature}
                                            </div>
                                        ))}
                                    </div>

                                    <Link
                                        href={route(
                                            "program.digital-directory.company-information",
                                            {
                                                package: pkg.name,
                                            },
                                        )}
                                        className="
                                            mt-10
                                            block
                                            rounded-2xl
                                            bg-slate-900
                                            px-6
                                            py-4
                                            text-center
                                            font-bold
                                            text-white
                                            transition
                                            hover:bg-slate-800
                                        "
                                    >
                                        {isEn
                                            ? "SELECT PACKAGE"
                                            : "PILIH PAKET"}
                                    </Link>
                                </div>
                            );
                        })}
                    </div>
                    {/* Actions */}

                    <div className="flex justify-start">
                        <Link
                            href={route("program.digital-directory")}
                            className="
        inline-flex
        items-center
        gap-2
        rounded-2xl
        border
        px-6
        py-4
        font-bold
        transition
        hover:bg-slate-100
    "
                        >
                            <ArrowLeft className="h-5 w-5" />

                            {isEn ? "BACK" : "KEMBALI"}
                        </Link>
                    </div>
                    {/* Footer */}

                    <div className="rounded-3xl bg-white p-8 text-center shadow-sm">
                        <p className="text-lg font-semibold">
                            {isEn
                                ? "All packages are billed annually and include access to future DIGESTEX enhancements."
                                : "Seluruh paket ditagihkan secara tahunan dan termasuk akses ke pengembangan DIGESTEX di masa mendatang."}
                        </p>
                    </div>
                </div>
                <StickyWhatsAppButton position="left" message="..." />
            </main>
        </div>
    );
}
