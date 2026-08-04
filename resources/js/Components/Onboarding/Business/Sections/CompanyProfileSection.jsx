/*
|--------------------------------------------------------------------------
| Company Profile Section™
|--------------------------------------------------------------------------
|
| Step 2
|
| Basic company profile information used for
| Company Intelligence™, Buyer Intelligence™,
| and Business Classification™.
|
|--------------------------------------------------------------------------
*/

import {
    Building2,
    CalendarDays,
    Users,
    Landmark,
    Factory,
} from "lucide-react";

export default function CompanyProfileSection({
    locale,
    data,
    setData,
    errors = {},
}) {
    const isEn = locale === "en";

    return (
        <section className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* =======================================================
                Header
            ======================================================= */}

            <div className="flex items-start gap-4">
                <div className="rounded-2xl bg-blue-100 p-3">
                    <Building2 className="h-6 w-6 text-blue-600" />
                </div>

                <div>
                    <h2 className="text-2xl font-black">
                        {isEn ? "Company Profile" : "Profil Perusahaan"}
                    </h2>

                    <p className="mt-2 text-sm leading-6 text-slate-500">
                        {isEn
                            ? "Basic company information used by DIGESTEX Company Intelligence™."
                            : "Informasi dasar perusahaan yang digunakan oleh DIGESTEX Company Intelligence™."}
                    </p>
                </div>
            </div>

            {/* =======================================================
                Form
            ======================================================= */}

            <div className="mt-8 grid gap-6 md:grid-cols-2">
                {/* Year Established */}

                <InputCard
                    icon={CalendarDays}
                    label={isEn ? "Year Established" : "Tahun Berdiri"}
                    value={data.year_established}
                    error={errors.year_established}
                    onChange={(value) => setData("year_established", value)}
                    placeholder="2005"
                />

                {/* Legal Entity */}

                <SelectCard
                    icon={Landmark}
                    label={isEn ? "Legal Entity" : "Bentuk Badan Usaha"}
                    value={data.legal_entity}
                    error={errors.legal_entity}
                    onChange={(value) => setData("legal_entity", value)}
                    options={[
                        {
                            value: "",
                            label: "-- Select --",
                        },
                        {
                            value: "pt",
                            label: "PT",
                        },
                        {
                            value: "pma",
                            label: "PT PMA",
                        },
                        {
                            value: "cv",
                            label: "CV",
                        },
                        {
                            value: "state_owned",
                            label: "State-Owned Enterprise",
                        },
                    ]}
                />

                {/* Employees */}

                <SelectCard
                    icon={Users}
                    label={isEn ? "Employees" : "Jumlah Karyawan"}
                    value={data.employee_range}
                    error={errors.employee_range}
                    onChange={(value) => setData("employee_range", value)}
                    options={[
                        {
                            value: "",
                            label: "-- Select --",
                        },
                        {
                            value: "1_50",
                            label: "1 - 50",
                        },
                        {
                            value: "51_200",
                            label: "51 - 200",
                        },
                        {
                            value: "201_500",
                            label: "201 - 500",
                        },
                        {
                            value: "501_1000",
                            label: "501 - 1,000",
                        },
                        {
                            value: "1000_plus",
                            label: "1,000+",
                        },
                    ]}
                />

                {/* Factory */}

                <InputCard
                    icon={Factory}
                    label={isEn ? "Factory Count" : "Jumlah Pabrik"}
                    value={data.factory_count}
                    error={errors.factory_count}
                    onChange={(value) => setData("factory_count", value)}
                    placeholder="1"
                />
            </div>

            {/* =======================================================
                Intelligence
            ======================================================= */}

            <div className="mt-8 rounded-2xl border border-blue-100 bg-blue-50 p-5">
                <div className="font-bold text-blue-700">
                    DIGESTEX Company Intelligence™
                </div>

                <p className="mt-2 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Company profile information helps buyers understand your business scale, corporate structure, and manufacturing capability."
                        : "Profil perusahaan membantu buyer memahami skala bisnis, struktur perusahaan, dan kemampuan manufaktur perusahaan Anda."}
                </p>
            </div>
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| Input Card
|--------------------------------------------------------------------------
*/

function InputCard({ icon: Icon, label, value, onChange, placeholder, error }) {
    return (
        <div>
            <label className="mb-2 flex items-center gap-2 font-semibold">
                <Icon className="h-4 w-4 text-slate-500" />

                {label}
            </label>

            <input
                type="text"
                value={value}
                placeholder={placeholder}
                onChange={(e) => onChange(e.target.value)}
                className={`w-full rounded-xl border px-4 py-3 focus:border-indigo-500 focus:outline-none ${
                    error ? "border-red-400" : "border-slate-300"
                }`}
            />

            {error && <p className="mt-1 text-sm text-red-600">{error}</p>}
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Select Card
|--------------------------------------------------------------------------
*/

function SelectCard({ icon: Icon, label, value, onChange, options, error }) {
    return (
        <div>
            <label className="mb-2 flex items-center gap-2 font-semibold">
                <Icon className="h-4 w-4 text-slate-500" />

                {label}
            </label>

            <select
                value={value}
                onChange={(e) => onChange(e.target.value)}
                className={`w-full rounded-xl border px-4 py-3 focus:border-indigo-500 focus:outline-none ${
                    error ? "border-red-400" : "border-slate-300"
                }`}
            >
                {options.map((option) => (
                    <option key={option.value} value={option.value}>
                        {option.label}
                    </option>
                ))}
            </select>

            {error && <p className="mt-1 text-sm text-red-600">{error}</p>}
        </div>
    );
}
