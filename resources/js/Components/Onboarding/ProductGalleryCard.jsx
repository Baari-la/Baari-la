import { usePage } from "@inertiajs/react";
import { Package, Image as ImageIcon, Upload, Star } from "lucide-react";

export default function ProductGalleryCard({ data, setData }) {
    const { locale } = usePage().props;

    const isEn = locale === "en";

    const updateFiles = (files) => {
        setData(files);
    };

    return (
        <div className="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm">
            <div className="mb-8">
                <h2 className="text-2xl font-black text-slate-900">
                    {isEn ? "Product Gallery" : "Galeri Produk"}
                </h2>

                <p className="mt-2 text-sm leading-6 text-slate-500">
                    {isEn
                        ? "Showcase your best products to buyers around the world through high-quality product images."
                        : "Tampilkan produk terbaik perusahaan kepada pembeli di seluruh dunia melalui foto produk berkualitas tinggi."}
                </p>
            </div>

            <UploadGallery
                title={isEn ? "Product Photos" : "Foto Produk"}
                subtitle={
                    isEn
                        ? "Upload your products, collections and featured items."
                        : "Unggah foto produk, koleksi dan produk unggulan."
                }
                files={data}
                onChange={updateFiles}
            />

            <div className="mt-8 rounded-2xl bg-slate-50 p-5">
                <div className="flex items-center gap-2">
                    <Package className="h-5 w-5 text-indigo-600" />

                    <span className="font-bold text-indigo-700">
                        {isEn
                            ? "Recommended Product Photos"
                            : "Foto Produk yang Direkomendasikan"}
                    </span>
                </div>

                <ul className="mt-4 space-y-2 text-sm text-slate-600">
                    <li>
                        ✓ {isEn ? "Best Selling Products" : "Produk Terlaris"}
                    </li>

                    <li>✓ {isEn ? "New Collection" : "Koleksi Terbaru"}</li>

                    <li>✓ {isEn ? "Featured Products" : "Produk Unggulan"}</li>

                    <li>✓ {isEn ? "Export Products" : "Produk Ekspor"}</li>

                    <li>
                        ✓{" "}
                        {isEn ? "Technical Textiles" : "Produk Tekstil Teknis"}
                    </li>

                    <li>✓ {isEn ? "Innovation Products" : "Produk Inovasi"}</li>
                </ul>
            </div>

            <div className="mt-6 rounded-2xl bg-amber-50 p-5">
                <div className="flex items-center gap-2">
                    <Star className="h-5 w-5 text-amber-600" />

                    <span className="font-bold text-amber-700">
                        {isEn ? "Buyer Tip" : "Tips untuk Buyer"}
                    </span>
                </div>

                <p className="mt-3 text-sm leading-6 text-slate-600">
                    {isEn
                        ? "Companies with complete product galleries generally receive higher engagement and better visibility in Smart Business Matching™."
                        : "Perusahaan dengan galeri produk yang lengkap umumnya memperoleh tingkat interaksi lebih tinggi dan visibilitas yang lebih baik dalam Smart Business Matching™."}
                </p>
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

                        {files?.length > 0
                            ? "Add More Photos"
                            : "Upload Photos"}

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
                            <div className="mb-3 font-semibold text-left text-emerald-700">
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
