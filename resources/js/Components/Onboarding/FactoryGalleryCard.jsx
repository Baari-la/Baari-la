import { usePage } from "@inertiajs/react";
import { Factory, Upload, Image as ImageIcon } from "lucide-react";

export default function FactoryGalleryCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const updateFiles = (files) => {
        setData(files);
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div className="mb-8">
                <h2 className="text-2xl font-black text-slate-900">
                    {isEn ? "Factory Gallery" : "Galeri Pabrik"}
                </h2>

                <p className="mt-2 text-sm leading-6 text-slate-500">
                    {isEn
                        ? "Show buyers your production facilities through high-quality factory photos."
                        : "Tampilkan fasilitas produksi perusahaan Anda melalui foto-foto pabrik berkualitas tinggi."}
                </p>
            </div>

            <UploadGallery
                title={isEn ? "Factory Photos" : "Foto Pabrik"}
                subtitle={
                    isEn
                        ? "Factory building, warehouse, production line, laboratory and showroom."
                        : "Gedung pabrik, gudang, lini produksi, laboratorium, dan showroom."
                }
                files={data}
                onChange={updateFiles}
            />

            <div className="mt-8 rounded-2xl bg-slate-50 p-5">
                <div className="flex items-center gap-2">
                    <Factory className="h-5 w-5 text-indigo-600" />

                    <span className="font-bold text-indigo-700">
                        {isEn
                            ? "Recommended Photos"
                            : "Foto yang Direkomendasikan"}
                    </span>
                </div>

                <ul className="mt-4 space-y-2 text-sm text-slate-600">
                    <li>✓ {isEn ? "Factory Building" : "Gedung Pabrik"}</li>

                    <li>✓ {isEn ? "Production Floor" : "Area Produksi"}</li>

                    <li>✓ {isEn ? "Warehouse" : "Gudang"}</li>

                    <li>✓ {isEn ? "Quality Laboratory" : "Laboratorium QC"}</li>

                    <li>✓ {isEn ? "Showroom" : "Showroom"}</li>

                    <li>✓ {isEn ? "Office" : "Perkantoran"}</li>
                </ul>
            </div>
        </div>
    );
}

function UploadGallery({ title, subtitle, files, onChange }) {
    return (
        <div>
            <label className="mb-3 block font-semibold">{title}</label>

            <div
                className="
                    rounded-2xl
                    border-2
                    border-dashed
                    border-slate-300
                    p-8
                    transition
                    hover:border-indigo-400
                    hover:bg-slate-50
                "
            >
                <div className="flex flex-col items-center text-center">
                    <ImageIcon className="h-10 w-10 text-slate-400" />

                    <h3 className="mt-4 font-bold text-slate-700">{title}</h3>

                    <p className="mt-2 max-w-md text-sm leading-6 text-slate-500">
                        {subtitle}
                    </p>

                    <label
                        className="
                            mt-6
                            inline-flex
                            cursor-pointer
                            items-center
                            gap-2
                            rounded-xl
                            bg-indigo-600
                            px-5
                            py-3
                            font-semibold
                            text-white
                            hover:bg-indigo-700
                        "
                    >
                        <Upload className="h-5 w-5" />
                        Upload Photos
                        <input
                            type="file"
                            multiple
                            accept="image/*"
                            onChange={(e) =>
                                onChange(Array.from(e.target.files || []))
                            }
                            className="hidden"
                        />
                    </label>

                    {files?.length > 0 && (
                        <div className="mt-8 w-full">
                            <div className="mb-3 text-left font-semibold text-emerald-700">
                                ✓ {files.length} file(s) selected
                            </div>

                            <div className="space-y-2">
                                {files.map((file, index) => (
                                    <div
                                        key={index}
                                        className="
                                            rounded-xl
                                            border
                                            border-emerald-200
                                            bg-emerald-50
                                            px-4
                                            py-2
                                            text-left
                                            text-sm
                                            text-emerald-700
                                        "
                                    >
                                        {file.name}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
