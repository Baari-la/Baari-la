/*
|--------------------------------------------------------------------------
| DIGESTEX Supply Chain Card™
|--------------------------------------------------------------------------
|
| Step 4
| Trade Profile™
|
| Launch Ready Version
|--------------------------------------------------------------------------
*/

import { usePage } from "@inertiajs/react";

import { Factory, Package, ShoppingBag, Store } from "lucide-react";

export default function SupplyChainCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const t = (en, id) => (isEn ? en : id);

    /*
    |--------------------------------------------------------------------------
    | Options
    |--------------------------------------------------------------------------
    */

    const industries = [
        {
            value: "GARMENT",
            icon: ShoppingBag,
            label: t("Garment Manufacturers", "Industri Garmen"),
        },

        {
            value: "KNITTING",
            icon: Factory,
            label: t("Knitting Industry", "Industri Rajut"),
        },

        {
            value: "WEAVING",
            icon: Factory,
            label: t("Weaving Industry", "Industri Tenun"),
        },

        {
            value: "HOME_TEXTILE",
            icon: Package,
            label: t("Home Textile", "Home Textile"),
        },

        {
            value: "FOOTWEAR",
            icon: ShoppingBag,
            label: t("Footwear", "Alas Kaki"),
        },

        {
            value: "BRAND_OWNER",
            icon: Store,
            label: t("Brand Owners", "Pemilik Merek"),
        },
    ];

    const selected = data.main_industries ?? [];

    const toggle = (value) => {
        if (selected.includes(value)) {
            setData(
                "main_industries",
                selected.filter((item) => item !== value),
            );

            return;
        }

        setData("main_industries", [...selected, value]);
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ------------------------------------------------ */}

            <h2 className="text-2xl font-black text-slate-900">
                {t("Supply Chain Intelligence™", "Supply Chain Intelligence™")}
            </h2>

            <p className="mt-3 max-w-3xl leading-7 text-slate-600">
                {t(
                    "Select the industries your company primarily supplies.",
                    "Pilih industri utama yang menjadi pelanggan perusahaan Anda.",
                )}
            </p>

            {/* ------------------------------------------------ */}

            <div className="mt-8 grid gap-5 md:grid-cols-2">
                {industries.map((item) => {
                    const Icon = item.icon;

                    const active = selected.includes(item.value);

                    return (
                        <button
                            key={item.value}
                            type="button"
                            onClick={() => toggle(item.value)}
                            className={`
                                rounded-2xl
                                border
                                p-5
                                text-left
                                transition

                                ${
                                    active
                                        ? "border-emerald-500 bg-emerald-50"
                                        : "border-slate-200 hover:border-emerald-300"
                                }
                            `}
                        >
                            <div className="flex items-center gap-4">
                                <div
                                    className={`
                                        rounded-xl
                                        p-3

                                        ${
                                            active
                                                ? "bg-emerald-600 text-white"
                                                : "bg-slate-100 text-slate-600"
                                        }
                                    `}
                                >
                                    <Icon className="h-6 w-6" />
                                </div>

                                <div className="flex-1">
                                    <div className="font-bold text-slate-900">
                                        {item.label}
                                    </div>
                                </div>

                                <div
                                    className={`
                                        flex
                                        h-6
                                        w-6
                                        items-center
                                        justify-center
                                        rounded-full
                                        border-2

                                        ${
                                            active
                                                ? "border-emerald-600 bg-emerald-600 text-white"
                                                : "border-slate-300"
                                        }
                                    `}
                                >
                                    {active && "✓"}
                                </div>
                            </div>
                        </button>
                    );
                })}
            </div>

            {/* ------------------------------------------------ */}

            <div className="mt-10 rounded-2xl border border-emerald-100 bg-emerald-50 p-6">
                <div className="font-bold text-emerald-700">
                    {t("Why does this matter?", "Mengapa ini penting?")}
                </div>

                <p className="mt-3 leading-7 text-slate-600">
                    {t(
                        "This information helps international buyers understand where your company fits within the textile supply chain. Future versions of DIGESTEX will use this information to power Smart Business Matching™ and Global Sourcing Hub™.",
                        "Informasi ini membantu buyer internasional memahami posisi perusahaan Anda dalam rantai pasok tekstil. Pada pengembangan berikutnya, data ini akan digunakan untuk Smart Business Matching™ dan Global Sourcing Hub™.",
                    )}
                </p>
            </div>
        </div>
    );
}
