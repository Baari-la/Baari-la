import ProgramNavbar from "@/Components/Program/ProgramNavbar";
import { Link, useForm, usePage } from "@inertiajs/react";
import { useEffect } from "react";
import StickyWhatsAppButton from "@/Components/Program/StickyWhatsAppButton";

import {
    Building2,
    User,
    Mail,
    Phone,
    Globe,
    MapPin,
    ArrowRight,
} from "lucide-react";

export default function Step3CompanyInformation({ selectedPackage }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const { data, setData, post, processing, errors } = useForm({
        package: selectedPackage ?? "Executive Partner",

        company_name: "",

        pic_name: "",

        position: "",

        email: "",

        phone: "",

        website: "",

        company_type: "",

        country: "Indonesia",

        city: "",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("program.digital-directory.company-information.store"), {
            preserveScroll: true,

            onSuccess: () => {},

            onError: () => {
                window.scrollTo({
                    top: 0,
                    behavior: "smooth",
                });
            },
        });
    };

    useEffect(() => {
        const saved = localStorage.getItem("digital-directory-company");

        if (saved) {
            const parsed = JSON.parse(saved);

            Object.keys(parsed).forEach((key) => {
                setData(key, parsed[key]);
            });
        }
    }, []);

    useEffect(() => {
        localStorage.setItem("digital-directory-company", JSON.stringify(data));
    }, [data]);

    return (
        <div className="min-h-screen bg-slate-50">
            <ProgramNavbar currentStep={3} />

            <main className="mx-auto max-w-7xl p-6">
                <div className="mx-auto max-w-5xl space-y-8">
                    {/* Header */}

                    <div className="text-center">
                        <p className="text-sm font-bold uppercase tracking-[0.2em] text-emerald-600">
                            STEP 3
                        </p>

                        <h1 className="mt-4 text-5xl font-black">
                            {isEn
                                ? "Company Information"
                                : "Informasi Perusahaan"}
                        </h1>

                        <p className="mt-4 text-lg text-slate-500">
                            {isEn
                                ? "Tell us about your company."
                                : "Beritahu kami tentang perusahaan Anda."}
                        </p>
                    </div>
                    {/* Tambahan */}
                    <div className="rounded-3xl bg-blue-50 p-6">
                        <h3 className="font-black text-blue-700">
                            {isEn
                                ? "Before You Continue"
                                : "Sebelum Melanjutkan"}
                        </h3>

                        <p className="mt-3 text-slate-600">
                            {isEn
                                ? "Please complete all required fields before proceeding to the next step."
                                : "Silakan lengkapi seluruh informasi yang wajib diisi sebelum melanjutkan ke langkah berikutnya."}
                        </p>
                    </div>
                    {/* INFO CARD */}

                    <div className="rounded-3xl bg-white p-8 shadow-sm">
                        <div className="grid gap-6 md:grid-cols-3">
                            <div>
                                <p className="text-sm text-slate-500">
                                    {isEn
                                        ? "Selected Package"
                                        : "Paket Terpilih"}
                                </p>

                                <p className="mt-2 text-xl font-black text-emerald-600">
                                    {data.package}
                                </p>
                            </div>

                            <div>
                                <p className="text-sm text-slate-500">
                                    {isEn
                                        ? "Completion Time"
                                        : "Estimasi Waktu"}
                                </p>

                                <p className="mt-2 text-xl font-black">
                                    2 Minutes
                                </p>
                            </div>

                            <div>
                                <p className="text-sm text-slate-500">
                                    {isEn ? "Progress" : "Progress"}
                                </p>

                                <p className="mt-2 text-xl font-black">50%</p>
                            </div>
                        </div>
                    </div>

                    {/* FORM */}
                    {/* Isi notifikasi */}
                    {Object.keys(errors).length > 0 && (
                        <div
                            className="
                rounded-3xl
                border
                border-red-200
                bg-red-50
                p-8
                shadow-sm
            "
                        >
                            <h3
                                className="
                    text-lg
                    font-black
                    text-red-700
                "
                            >
                                {isEn
                                    ? "Please complete the following information:"
                                    : "Silakan lengkapi informasi berikut:"}
                            </h3>

                            <ul
                                className="
                    mt-4
                    list-disc
                    space-y-2
                    pl-5
                    text-red-600
                "
                            >
                                {Object.values(errors).map((error, index) => (
                                    <li key={index}>{error}</li>
                                ))}
                            </ul>
                        </div>
                    )}

                    <form
                        onSubmit={submit}
                        className="
                            rounded-3xl
                            border
                            bg-white
                            p-10
                            shadow-sm
                        "
                    >
                        {/* Selected Package */}

                        <div className="mb-8 rounded-2xl bg-emerald-50 p-6">
                            <p className="text-sm font-semibold text-emerald-700">
                                {isEn ? "Selected Package" : "Paket Terpilih"}
                            </p>

                            <p className="mt-2 text-2xl font-black text-emerald-700">
                                {data.package}
                            </p>
                        </div>

                        <div className="grid gap-6 md:grid-cols-2">
                            <Input
                                icon={Building2}
                                label={
                                    isEn
                                        ? "Company Name *"
                                        : "Nama Perusahaan *"
                                }
                                value={data.company_name}
                                error={errors.company_name}
                                onChange={(e) =>
                                    setData("company_name", e.target.value)
                                }
                            />

                            <Input
                                icon={User}
                                label="PIC Name"
                                value={data.pic_name}
                                onChange={(e) =>
                                    setData("pic_name", e.target.value)
                                }
                            />

                            <Input
                                icon={User}
                                label={isEn ? "Position" : "Jabatan"}
                                value={data.position}
                                onChange={(e) =>
                                    setData("position", e.target.value)
                                }
                            />

                            <Input
                                icon={Mail}
                                label="Email"
                                value={data.email}
                                onChange={(e) =>
                                    setData("email", e.target.value)
                                }
                            />

                            <Input
                                icon={Phone}
                                label={isEn ? "Phone" : "Telepon"}
                                value={data.phone}
                                onChange={(e) =>
                                    setData("phone", e.target.value)
                                }
                            />

                            <Input
                                icon={Globe}
                                label="Website"
                                value={data.website}
                                onChange={(e) =>
                                    setData("website", e.target.value)
                                }
                            />

                            {/* Company Type */}

                            <div>
                                <label className="font-semibold">
                                    {isEn ? "Company Type" : "Jenis Perusahaan"}
                                </label>

                                <select
                                    value={data.company_type}
                                    onChange={(e) =>
                                        setData("company_type", e.target.value)
                                    }
                                    className={`
        mt-2
        w-full
        rounded-xl
        p-3

        ${errors.company_type ? "border-red-500" : "border-slate-300"}
    `}
                                >
                                    <option value="">--</option>

                                    <option>Fiber Producer</option>

                                    <option>Yarn Manufacturer</option>

                                    <option>Fabric Manufacturer</option>

                                    <option>Garment Manufacturer</option>

                                    <option>Textile Machinery</option>

                                    <option>Chemical Supplier</option>

                                    <option>Brand / Retailer</option>

                                    <option>Technology Provider</option>
                                </select>
                            </div>

                            {/* Country */}

                            <Input
                                icon={Globe}
                                label="Country"
                                value={data.country}
                                onChange={(e) =>
                                    setData("country", e.target.value)
                                }
                            />

                            {/* City */}

                            <Input
                                icon={MapPin}
                                label="City"
                                value={data.city}
                                onChange={(e) =>
                                    setData("city", e.target.value)
                                }
                            />
                        </div>

                        {/* Footer */}

                        {/* Footer form */}
                        <div className="mt-10 flex items-center justify-between">
                            <Link
                                href={route(
                                    "program.digital-directory.package",
                                )}
                                className="
            rounded-2xl
            border
            px-6
            py-4
            font-bold
        "
                            >
                                {isEn ? "BACK" : "KEMBALI"}
                            </Link>

                            <button
                                type="submit"
                                disabled={processing}
                                className="
            inline-flex
            items-center
            gap-2
            rounded-2xl
            bg-slate-900
            px-8
            py-4
            font-bold
            text-white
        "
                            >
                                {isEn ? "CONTINUE" : "LANJUTKAN"}

                                <ArrowRight className="h-5 w-5" />
                            </button>
                        </div>
                        {/* Batas Footer form */}
                    </form>
                </div>
                <StickyWhatsAppButton position="right" message="..." />
            </main>
        </div>
    );
}

function Input({ icon: Icon, label, value, onChange, error }) {
    return (
        <div>
            <label className="font-semibold">{label}</label>

            <div className="relative mt-2">
                <Icon className="absolute left-3 top-3.5 h-5 w-5 text-slate-400" />

                <input
                    value={value}
                    onChange={onChange}
                    className={`
        w-full
        rounded-xl
        py-3
        pl-11
        pr-4

        ${error ? "border-red-500" : "border-slate-300"}
    `}
                />
            </div>
            {error && (
                <p
                    className="
                mt-2
                text-sm
                font-medium
                text-red-500
            "
                >
                    {error}
                </p>
            )}
        </div>
    );
}
