/*
|--------------------------------------------------------------------------
| DIGESTEX Location Card™
|--------------------------------------------------------------------------
|
| Reusable location form used by:
|
| • Head Office
| • Factory
| • Warehouse
| • Branch Office
|
|--------------------------------------------------------------------------
*/
import { usePage } from "@inertiajs/react";
import TextField from "../../Common/Forms/TextField";

import { Building2 } from "lucide-react";

export default function LocationCard({
    blueprint,
    location = {},
    onChange,
    TextareaComponent,
}) {
    const { locale } = usePage().props;
    const isEn = locale === "en";
    const Icon = blueprint?.icon ?? Building2;

    // Helper Translation
    const t = (en, id) => (isEn ? en : id);

    // Title & Subtitle Dynamic
    const title = t(blueprint?.title, blueprint?.titleId);
    const subtitle = blueprint?.multiple
        ? t(
              "You may add multiple locations.",
              "Anda dapat menambahkan beberapa lokasi.",
          )
        : t("Primary company location.", "Lokasi utama perusahaan.");

    // Safe Change Handler
    const handleChange = (field, value) => {
        onChange?.(field, value);
    };

    // Pilihan komponen untuk Address (Gunakan TextareaComponent jika dikirim, atau TextField sebagai fallback)
    const AddressField = TextareaComponent ?? TextField;

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            {/* ===========================================================
                Header
            =========================================================== */}
            <div className="mb-8 flex items-center gap-4">
                <div
                    className="
                        flex
                        h-14
                        w-14
                        items-center
                        justify-center
                        rounded-2xl
                        bg-emerald-100
                        text-emerald-700
                    "
                >
                    <Icon className="h-7 w-7" />
                </div>

                <div>
                    <h3 className="text-xl font-black text-slate-900">
                        {title}
                    </h3>

                    <p className="mt-1 text-sm text-slate-500">{subtitle}</p>
                </div>
            </div>

            {/* ===========================================================
                Identity
            =========================================================== */}
            <div className="grid gap-6 md:grid-cols-3">
                <TextField
                    label={t("Location Name", "Nama Lokasi")}
                    value={location.location_name ?? ""}
                    onChange={(e) =>
                        handleChange("location_name", e.target.value)
                    }
                    placeholder={t("Bandung Factory", "Pabrik Bandung")}
                />

                <TextField
                    label={t("Location Label", "Label Lokasi")}
                    value={location.location_label ?? ""}
                    onChange={(e) =>
                        handleChange("location_label", e.target.value)
                    }
                    placeholder={t("Spinning Division", "Divisi Pemintalan")}
                />

                <TextField
                    readOnly
                    label={t("Location Code", "Kode Lokasi")}
                    value={location.location_code ?? ""}
                    onChange={(e) =>
                        handleChange("location_code", e.target.value)
                    }
                    placeholder={t("Auto-generated", "Otomatis oleh sistem")}
                />
            </div>

            {/* ===========================================================
                Address
            =========================================================== */}
            <div className="mt-8">
                {/* Full Width Address (Textarea/TextField) */}
                <AddressField
                    rows={3}
                    label={t("Address", "Alamat")}
                    value={location.address ?? ""}
                    onChange={(e) =>
                        handleChange("address", e?.target ? e.target.value : e)
                    }
                    placeholder={t(
                        "Jl. Raya Soekarno Hatta No.123, Kawasan Industri...",
                        "Jl. Raya Soekarno Hatta No.123, Kawasan Industri...",
                    )}
                />

                {/* Country, Province, City */}
                <div className="mt-6 grid gap-6 md:grid-cols-3">
                    <TextField
                        label={t("Country", "Negara")}
                        value={location.country ?? ""}
                        onChange={(e) =>
                            handleChange("country", e.target.value)
                        }
                        placeholder={t("INDONESIA", "INDONESIA")}
                    />

                    <TextField
                        label={t("Province", "Provinsi")}
                        value={location.province ?? ""}
                        onChange={(e) =>
                            handleChange("province", e.target.value)
                        }
                        placeholder={t("West Java", "Jawa Barat")}
                    />

                    <TextField
                        label={t("City", "Kota / Kabupaten")}
                        value={location.city ?? ""}
                        onChange={(e) => handleChange("city", e.target.value)}
                        placeholder={t("Bandung", "Bandung")}
                    />
                </div>

                {/* District, Subdistrict, Postal Code */}
                <div className="mt-6 grid gap-6 md:grid-cols-3">
                    <TextField
                        label={t("District", "Kecamatan")}
                        value={location.district ?? ""}
                        onChange={(e) =>
                            handleChange("district", e.target.value)
                        }
                        placeholder={t("Cibeunying Kaler", "Cibeunying Kaler")}
                    />

                    <TextField
                        label={t("Subdistrict", "Kelurahan / Desa")}
                        value={location.subdistrict ?? ""}
                        onChange={(e) =>
                            handleChange("subdistrict", e.target.value)
                        }
                        placeholder={t("Cigadung", "Cigadung")}
                    />

                    <TextField
                        label={t("Postal Code", "Kode Pos")}
                        value={location.postal_code ?? ""}
                        onChange={(e) =>
                            handleChange("postal_code", e.target.value)
                        }
                        placeholder="40123"
                    />
                </div>
            </div>

            {/* ===========================================================
                Contact
            =========================================================== */}
            <div className="mt-8 grid gap-6 md:grid-cols-2">
                <TextField
                    label={t("Contact Person", "Penanggung Jawab")}
                    value={location.contact_person ?? ""}
                    onChange={(e) =>
                        handleChange("contact_person", e.target.value)
                    }
                    placeholder={t("John Doe", "John Doe")}
                />

                <TextField
                    label={t("Phone", "Telepon")}
                    value={location.phone ?? ""}
                    onChange={(e) => handleChange("phone", e.target.value)}
                    placeholder="+62 22 1234567"
                />

                <TextField
                    type="email"
                    label={t("Email", "Email")}
                    value={location.email ?? ""}
                    onChange={(e) => handleChange("email", e.target.value)}
                    placeholder="factory@company.com"
                />

                <TextField
                    label={t("Website", "Website")}
                    value={location.website ?? ""}
                    onChange={(e) => handleChange("website", e.target.value)}
                    placeholder="https://company.com"
                />
            </div>

            {/* ===========================================================
                Maps (Full Width)
            =========================================================== */}
            <div className="mt-8">
                <TextField
                    label={t("Google Maps URL", "Tautan Google Maps")}
                    value={location.google_maps_url ?? ""}
                    onChange={(e) =>
                        handleChange("google_maps_url", e.target.value)
                    }
                    placeholder={t(
                        "Paste Google Maps URL",
                        "Tempel tautan Google Maps",
                    )}
                />
            </div>

            {/* ===========================================================
                Status Section
            =========================================================== */}
            <div className="mt-10 rounded-2xl border border-slate-200 bg-slate-50 p-6">
                <h4 className="font-bold text-slate-800">
                    {t("Operational Status", "Status Operasional")}
                </h4>

                <div className="mt-4 flex flex-wrap gap-6 text-sm">
                    {/* Primary Location Option */}
                    <label className="flex cursor-pointer items-center gap-2 font-medium text-slate-700">
                        <input
                            type="checkbox"
                            checked={Boolean(location.is_primary)}
                            onChange={(e) =>
                                handleChange("is_primary", e.target.checked)
                            }
                            className="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                        />
                        <span>
                            {t(
                                "Primary Business Location",
                                "Lokasi Bisnis Utama",
                            )}
                        </span>
                    </label>

                    {/* Active Location Option */}
                    <label className="flex cursor-pointer items-center gap-2 font-medium text-slate-700">
                        <input
                            type="checkbox"
                            checked={location.is_active ?? true}
                            onChange={(e) =>
                                handleChange("is_active", e.target.checked)
                            }
                            className="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                        />
                        <span>{t("Active Location", "Lokasi Aktif")}</span>
                    </label>
                </div>
            </div>
        </div>
    );
}
