import { Head, useForm, usePage } from "@inertiajs/react";

import BaseOnboardingPage from "@/Components/Onboarding/BaseOnboardingPage";

import CompanyInformationCard from "@/Components/Onboarding/CompanyInformationCard";
import CompanySummaryCard from "@/Components/Onboarding/CompanySummaryCard";
import StepNavigation from "@/Components/Onboarding/StepNavigation";

export default function CompanyInformation() {
    const { auth, locale, company } = usePage().props;
    const companyFound = company?.profile_exists === true;
    const isEn = locale === "en";

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */

    const { data, setData, post, processing } = useForm({
        company_name: company?.nama_perusahaan ?? "",

        pic_name: company?.pic_name ?? auth.user.name ?? "",

        position: company?.position ?? "",

        email: company?.email ?? auth.user.email ?? "",

        phone: company?.phone ?? "",

        website: company?.website ?? "",

        company_type: company?.company_type ?? "",

        country: company?.country ?? "Indonesia",

        city: company?.city ?? "",
    });

    /*
    |--------------------------------------------------------------------------
    | Submit
    |--------------------------------------------------------------------------
    */

    const submit = (e) => {
        e.preventDefault();

        console.log("===== SUBMIT CLICKED =====");

        post(route("onboarding.company-information.store"), {
            preserveScroll: true,

            onStart: () => console.log("POST START"),

            onSuccess: () => console.log("POST SUCCESS"),

            onError: (errors) => console.log("POST ERROR", errors),

            onFinish: () => console.log("POST FINISH"),
        });
    };

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <BaseOnboardingPage
            step={1}
            /*
            |--------------------------------------------------------------------------
            | Header
            |--------------------------------------------------------------------------
            */

            header={{
                title: isEn ? "Company Identity" : "Identitas Perusahaan",

                description: isEn
                    ? "Build the foundation of your Digital Company Passport™. Complete your company identity to power Executive Dashboard™, Company Intelligence™, and Smart Business Matching™."
                    : "Bangun fondasi Digital Company Passport™ Anda. Lengkapi identitas perusahaan sebagai dasar Executive Dashboard™, Company Intelligence™, dan Smart Business Matching™.",
            }}
            /*
            |--------------------------------------------------------------------------
            | Intelligence Box
            |--------------------------------------------------------------------------
            */

            intelligence={{
                title: isEn ? "Company Intelligence™" : "Company Intelligence™",

                description: isEn
                    ? "The information you provide will become the foundation for multiple DIGESTEX intelligence services."
                    : "Informasi yang Anda lengkapi akan menjadi fondasi berbagai layanan intelijen DIGESTEX.",

                items: [
                    "Digital Company Passport™",

                    "Executive Dashboard™",

                    "Company Intelligence™",

                    "Smart Business Matching™",

                    "Buyer Readiness™",

                    isEn ? "Public Company Directory" : "Direktori Perusahaan",
                ],
            }}
            /*
            |--------------------------------------------------------------------------
            | Summary
            |--------------------------------------------------------------------------
            */

            sidebar={<CompanySummaryCard data={data} />}
            /*
            |--------------------------------------------------------------------------
            | Navigation
            |--------------------------------------------------------------------------
            */
        >
            <form onSubmit={submit} className="space-y-8">
                {/* ======================================================
     | Welcome Card
     ====================================================== */}

                <div
                    className="
            rounded-3xl
            border
            border-indigo-100
            bg-gradient-to-r
            from-indigo-50
            via-white
            to-emerald-50
            p-8
        "
                >
                    <div className="flex items-start justify-between">
                        <div>
                            <div className="text-sm font-black uppercase tracking-wider text-indigo-600">
                                {isEn
                                    ? "Welcome Back"
                                    : "Selamat Datang Kembali"}
                            </div>

                            <h2 className="mt-2 text-3xl font-black text-slate-900">
                                {auth?.user?.name}
                            </h2>

                            <p className="mt-4 max-w-3xl leading-7 text-slate-600">
                                {isEn
                                    ? "Your Digital Company Passport™ starts here. The information below becomes the foundation for Executive Dashboard™, Company Intelligence™, Smart Business Matching™, and future DIGESTEX services."
                                    : "Digital Company Passport™ Anda dimulai dari sini. Informasi berikut akan menjadi fondasi Executive Dashboard™, Company Intelligence™, Smart Business Matching™, serta layanan DIGESTEX berikutnya."}
                            </p>
                        </div>

                        <div
                            className="
                    hidden
                    rounded-2xl
                    bg-white
                    px-5
                    py-4
                    text-center
                    shadow-sm
                    lg:block
                "
                        >
                            <div className="text-xs font-semibold uppercase text-slate-500">
                                {isEn ? "Status" : "Status"}
                            </div>

                            <div className="mt-2 inline-flex rounded-full bg-amber-100 px-3 py-1 text-sm font-bold text-amber-700">
                                Draft
                            </div>
                        </div>
                    </div>
                </div>

                {/* ======================================================
     | Verified Company Identity
     ====================================================== */}

                {companyFound && (
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
                            <div
                                className="
                        flex
                        h-12
                        w-12
                        items-center
                        justify-center
                        rounded-full
                        bg-emerald-600
                        text-xl
                        font-black
                        text-white
                    "
                            >
                                ✓
                            </div>

                            <div className="flex-1">
                                <div
                                    className="
                            text-sm
                            font-black
                            uppercase
                            tracking-wider
                            text-emerald-700
                        "
                                >
                                    {isEn
                                        ? "Verified Company Identity"
                                        : "Identitas Perusahaan Terverifikasi"}
                                </div>

                                <h2 className="mt-2 text-2xl font-black text-slate-900">
                                    {company?.nama_perusahaan}
                                </h2>

                                <p className="mt-4 max-w-3xl leading-7 text-slate-600">
                                    {isEn
                                        ? "Your company has been successfully matched with the DIGESTEX Canonical Company Identity. Some information has already been pre-filled for your review."
                                        : "Perusahaan Anda berhasil dicocokkan dengan DIGESTEX Canonical Company Identity. Beberapa informasi telah diisi otomatis untuk ditinjau."}
                                </p>

                                <div className="mt-6 grid gap-3 md:grid-cols-3">
                                    <Badge>
                                        {isEn
                                            ? "Verified Identity"
                                            : "Identitas Terverifikasi"}
                                    </Badge>

                                    <Badge>
                                        {isEn
                                            ? "Existing Company Record"
                                            : "Data Perusahaan Ditemukan"}
                                    </Badge>

                                    <Badge>
                                        {isEn
                                            ? "Ready for Digital Passport"
                                            : "Siap untuk Digital Passport"}
                                    </Badge>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* ======================================================
     | Company Identity
     ====================================================== */}

                <CompanyInformationCard data={data} setData={setData} />

                {/* ======================================================
     | Navigation
     ====================================================== */}

                <StepNavigation currentStep={1} processing={processing} />
            </form>
        </BaseOnboardingPage>
    );
}

function Badge({ children }) {
    return (
        <div
            className="
                flex
                items-center
                gap-2
                rounded-xl
                border
                border-emerald-200
                bg-white
                px-4
                py-3
                shadow-sm
            "
        >
            <div
                className="
                    flex
                    h-6
                    w-6
                    items-center
                    justify-center
                    rounded-full
                    bg-emerald-600
                    text-xs
                    font-bold
                    text-white
                "
            >
                ✓
            </div>

            <span className="text-sm font-semibold text-slate-700">
                {children}
            </span>
        </div>
    );
}
