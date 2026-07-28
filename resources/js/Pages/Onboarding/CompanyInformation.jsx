import OnboardingLayout from "@/Layouts/OnboardingLayout";
import OnboardingNavbar from "@/Components/Onboarding/OnboardingNavbar";
import StickyWhatsAppButton from "@/Components/Program/StickyWhatsAppButton";

import { Head, useForm, usePage } from "@inertiajs/react";

import {
    Building2,
    User,
    Mail,
    Phone,
    Globe,
    MapPin,
    ArrowRight,
} from "lucide-react";

export default function CompanyInformation() {
    const { locale, auth, company } = usePage().props;

    const isEn = locale === "en";

    const { data, setData, post, processing, errors } = useForm({
        company_name: company?.nama_perusahaan ?? "",

        pic_name: auth?.user?.name ?? "",

        position: "",

        email: auth?.user?.email ?? "",

        phone: company?.telepon ?? "",

        website: company?.email_web ?? "",

        company_type: company?.category ?? "",

        country: company?.country ?? "Indonesia",

        city: company?.city ?? "",

        address: company?.alamat_lengkap ?? "",

        province: company?.province ?? "",

        postal_code: company?.postal_code ?? "",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("onboarding.company-information.store"));
    };

    return (
        <OnboardingLayout>
            <Head title="Company Information" />

            <div className="min-h-screen bg-slate-50">
                <OnboardingNavbar currentStep={1} />

                <main className="mx-auto max-w-7xl p-6">
                    <div className="mx-auto max-w-5xl space-y-8">
                        {/* Header */}

                        <div className="text-center">
                            <p className="text-sm font-black uppercase tracking-[0.2em] text-emerald-600">
                                STEP 1
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

                        {/* Welcome */}

                        <div className="rounded-3xl bg-emerald-50 p-8">
                            <h3 className="text-2xl font-black text-emerald-700">
                                {isEn
                                    ? `Welcome, ${auth?.user?.name}`
                                    : `Selamat Datang, ${auth?.user?.name}`}
                            </h3>

                            <p className="mt-3 text-slate-600">
                                {isEn
                                    ? "Let's begin building your Digital Company Passport™."
                                    : "Mari mulai membangun Digital Company Passport™ Anda."}
                            </p>
                        </div>

                        {/* Tambahan */}
                        {company && (
                            <div
                                className="
            rounded-3xl
            border
            border-emerald-200
            bg-emerald-50
            p-8
        "
                            >
                                <div className="flex items-start gap-4">
                                    <div className="rounded-2xl bg-emerald-500 p-3">
                                        <Building2 className="h-6 w-6 text-white" />
                                    </div>

                                    <div>
                                        <div className="font-black text-emerald-700">
                                            COMPANY FOUND
                                        </div>

                                        <div className="mt-2 text-xl font-black">
                                            {company.nama_perusahaan}
                                        </div>

                                        <p className="mt-3 text-slate-600">
                                            DIGESTEX has found your company in
                                            our directory. Some information has
                                            been pre-filled for your review.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        )}
                        {/* Form */}

                        <form
                            onSubmit={submit}
                            className="rounded-3xl bg-white p-10 shadow-sm"
                        >
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
                                    label={
                                        isEn ? "Phone Number" : "Nomor Telepon"
                                    }
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

                                <div>
                                    <label className="font-semibold">
                                        {isEn
                                            ? "Company Type"
                                            : "Jenis Perusahaan"}
                                    </label>

                                    <select
                                        value={data.company_type}
                                        onChange={(e) =>
                                            setData(
                                                "company_type",
                                                e.target.value,
                                            )
                                        }
                                        className="mt-2 w-full rounded-xl border border-slate-300 p-3"
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

                                <Input
                                    icon={Globe}
                                    label="Country"
                                    value={data.country}
                                    onChange={(e) =>
                                        setData("country", e.target.value)
                                    }
                                />

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

                            <div className="mt-10 flex justify-end">
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
                        </form>
                    </div>

                    <StickyWhatsAppButton
                        position="right"
                        message="DIGESTEX Company Onboarding"
                    />
                </main>
            </div>
        </OnboardingLayout>
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
                        border
                        py-3
                        pl-11
                        pr-4

                        ${error ? "border-red-500" : "border-slate-300"}
                    `}
                />
            </div>

            {error && <p className="mt-2 text-sm text-red-500">{error}</p>}
        </div>
    );
}
