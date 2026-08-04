import { usePage } from "@inertiajs/react";
import { Building2, User, Mail, Phone, Globe, MapPin } from "lucide-react";

export default function CompanyInformationCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const update = (field, value) => {
        setData({
            ...data,
            [field]: value,
        });
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* Header */}

            <div className="mb-8">
                <h2 className="text-2xl font-black text-slate-900">
                    {isEn ? "Company Information" : "Informasi Perusahaan"}
                </h2>

                <p className="mt-2 text-sm leading-6 text-slate-500">
                    {isEn
                        ? "Basic company identity that will become the foundation of your Digital Company Passport™."
                        : "Informasi identitas perusahaan yang akan menjadi fondasi Digital Company Passport™."}
                </p>
            </div>

            <div className="grid gap-6 md:grid-cols-2">
                <Input
                    icon={Building2}
                    label={isEn ? "Company Name *" : "Nama Perusahaan *"}
                    value={data.company_name}
                    onChange={(v) => update("company_name", v)}
                />

                <Input
                    icon={User}
                    label={isEn ? "Contact Person" : "PIC"}
                    value={data.contact_person}
                    onChange={(v) => update("contact_person", v)}
                />

                <Input
                    icon={Mail}
                    label={isEn ? "Email" : "Email"}
                    type="email"
                    value={data.email}
                    onChange={(v) => update("email", v)}
                />

                <Input
                    icon={Phone}
                    label={isEn ? "Phone Number" : "Nomor Telepon"}
                    value={data.phone}
                    onChange={(v) => update("phone", v)}
                />

                <Input
                    icon={Globe}
                    label={isEn ? "Website" : "Website"}
                    value={data.website}
                    onChange={(v) => update("website", v)}
                />

                <Input
                    icon={MapPin}
                    label={isEn ? "Country" : "Negara"}
                    value={data.country}
                    onChange={(v) => update("country", v)}
                />

                <Input
                    icon={MapPin}
                    label={isEn ? "Province" : "Provinsi"}
                    value={data.province}
                    onChange={(v) => update("province", v)}
                />

                <Input
                    icon={MapPin}
                    label={isEn ? "City" : "Kota"}
                    value={data.city}
                    onChange={(v) => update("city", v)}
                />
            </div>

            {/* Passport */}

            <div className="mt-8 rounded-2xl bg-slate-50 p-6">
                <div className="font-black text-indigo-700">
                    Digital Company Passport™
                </div>

                <p className="mt-3 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "This information will be used throughout the DIGESTEX ecosystem including Executive Dashboard™, Company Intelligence™, Smart Business Matching™, and Public Company Directory."
                        : "Informasi ini akan digunakan di seluruh ekosistem DIGESTEX termasuk Executive Dashboard™, Company Intelligence™, Smart Business Matching™, dan Public Company Directory."}
                </p>
            </div>
        </div>
    );
}

function Input({ icon: Icon, label, value, onChange, type = "text" }) {
    return (
        <div>
            <label className="block font-semibold text-slate-700">
                {label}
            </label>

            <div className="relative mt-2">
                <Icon className="absolute left-4 top-3.5 h-5 w-5 text-slate-400" />

                <input
                    type={type}
                    value={value ?? ""}
                    onChange={(e) => onChange(e.target.value)}
                    className="
                        w-full
                        rounded-xl
                        border
                        border-slate-300
                        py-3
                        pl-12
                        pr-4
                        transition
                        focus:border-indigo-500
                        focus:outline-none
                        focus:ring-2
                        focus:ring-indigo-100
                    "
                />
            </div>
        </div>
    );
}
