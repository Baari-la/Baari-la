import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from "@inertiajs/react";
import { useState } from "react";
import { CKEditor } from "@ckeditor/ckeditor5-react";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default function Edit({ news }) {
    const [lang, setLang] = useState("id");

    const [preview, setPreview] = useState(
        news.image ? `/storage/${news.image}` : null,
    );

    const [isTranslating, setIsTranslating] = useState(false);
    const handleImageChange = (e) => {
        const file = e.target.files[0];

        if (file) {
            setData("image", file);
            setPreview(URL.createObjectURL(file));
        }
    };
    // Mengambil data lama dari database ke dalam form
    const { data, setData, put, processing } = useForm({
        category: news.category || "Industry News",
        title_id: news.title_id || "",
        summary_id: news.summary_id || "",
        content_id: news.content_id || "",
        slug: news.slug || "",
        title_en: news.title_en || "",
        summary_en: news.summary_en || "",
        content_en: news.content_en || "",

        partner_name: news.partner_name || "",
        meta_title: news.meta_title || "",
        meta_description: news.meta_description || "",
        meta_keywords: news.meta_keywords || "",
        image: null,
    });

    const submit = (e) => {
        e.preventDefault();

        put(route("admin.news.update", news.slug));
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="font-bold text-xl text-gray-800">
                    Edit Intelligence News
                </h2>
            }
        >
            <Head title="Edit News" />
            <div className="py-12 bg-gray-50">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="flex gap-4 mb-6">
                        <button
                            type="button"
                            onClick={() => setLang("id")}
                            className={
                                lang === "id"
                                    ? "font-bold text-yellow-500"
                                    : "text-gray-400"
                            }
                        >
                            INDONESIA
                        </button>
                        <button
                            type="button"
                            onClick={() => setLang("en")}
                            className={
                                lang === "en"
                                    ? "font-bold text-yellow-500"
                                    : "text-gray-400"
                            }
                        >
                            ENGLISH
                        </button>
                    </div>
                    <form
                        onSubmit={submit}
                        className="bg-white p-10 rounded-[40px] shadow-sm border border-gray-100"
                    >
                        {/* UPLOAD GAMBAR */}
                        <div className="mb-8 p-6 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3">
                                Intelligence Visual (Header Image)
                            </label>
                            <div className="flex items-center gap-6">
                                {preview ? (
                                    <img
                                        src={preview}
                                        className="w-32 h-32 object-cover rounded-2xl border-4 border-white shadow-lg"
                                        alt="Preview"
                                    />
                                ) : (
                                    <div className="w-32 h-32 bg-gray-200 rounded-2xl flex items-center justify-center text-[10px] text-gray-400 font-bold uppercase text-center p-4 italic">
                                        No Image Selected
                                    </div>
                                )}
                                <input
                                    type="file"
                                    onChange={handleImageChange}
                                    className="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-[#0a192f] file:text-yellow-500 hover:file:bg-navy-800 transition-colors"
                                />
                            </div>
                        </div>
                        {/* CATEGORY */}

                        <div className="mb-8">
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                                Intelligence Category
                            </label>

                            <select
                                value={data.category}
                                onChange={(e) =>
                                    setData("category", e.target.value)
                                }
                                className="w-full p-4 rounded-xl border border-slate-300 bg-white text-slate-900"
                            >
                                <option value="Market Intelligence">
                                    Market Intelligence
                                </option>

                                <option value="Trade & Policy">
                                    Trade & Policy
                                </option>

                                <option value="Sustainability">
                                    Sustainability
                                </option>

                                <option value="Technology & Innovation">
                                    Technology & Innovation
                                </option>

                                <option value="Industry News">
                                    Industry News
                                </option>

                                <option value="Events & Exhibitions">
                                    Events & Exhibitions
                                </option>
                            </select>
                        </div>
                        {/* INPUT JUDUL */}
                        <div
                            className={lang === "id" ? "block mb-8" : "hidden"}
                        >
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                                {" "}
                                Judul (ID){" "}
                            </label>
                            <input
                                type="text"
                                value={data.title_id}
                                className="w-full p-4 rounded-xl border border-slate-300 bg-white text-slate-900"
                                onChange={(e) =>
                                    setData("title_id", e.target.value)
                                }
                            />
                            <p className="mt-2 text-[9px] font-mono text-gray-400">
                                Slug: {data.slug}
                            </p>
                        </div>
                        {/* Summary ID */}
                        <div
                            className={lang === "id" ? "block mb-8" : "hidden"}
                        >
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                                Executive Summary (ID)
                            </label>

                            <textarea
                                rows={4}
                                value={data.summary_id}
                                onChange={(e) =>
                                    setData("summary_id", e.target.value)
                                }
                                className="w-full p-4 rounded-xl border border-slate-300 bg-white text-slate-900"
                                placeholder="Ringkasan utama artikel..."
                            />
                        </div>
                        <div
                            className={lang === "en" ? "block mb-8" : "hidden"}
                        >
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                                Title (EN){" "}
                                {isTranslating && "- AI Translating..."}
                            </label>
                            <input
                                type="text"
                                value={data.title_en}
                                className="w-full p-4 rounded-xl border-gray-100 bg-gray-50 font-bold text-blue-600"
                                onChange={(e) =>
                                    setData("title_en", e.target.value)
                                }
                            />
                        </div>
                        {/* Summary EN */}
                        <div
                            className={lang === "en" ? "block mb-8" : "hidden"}
                        >
                            <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                                Executive Summary (EN)
                            </label>

                            <textarea
                                rows={4}
                                value={data.summary_en}
                                onChange={(e) =>
                                    setData("summary_en", e.target.value)
                                }
                                className="w-full p-4 rounded-xl border border-slate-300 bg-gray-50 text-slate-900"
                                placeholder="Short article summary..."
                            />
                        </div>
                        {/* EDITOR KONTEN */}
                        <div
                            className={lang === "id" ? "block mb-8" : "hidden"}
                        >
                            <label className="block text-[10px] font-black text-[#0a192f] uppercase mb-2">
                                {" "}
                                Konten Indonesia{" "}
                            </label>
                            <CKEditor
                                editor={ClassicEditor}
                                data={data.content_id}
                                onChange={(event, editor) =>
                                    setData("content_id", editor.getData())
                                }
                            />
                        </div>

                        <div
                            className={lang === "en" ? "block mb-8" : "hidden"}
                        >
                            <label className="block text-[10px] font-black text-blue-600 uppercase mb-2">
                                {" "}
                                English Content (Auto-Drafted){" "}
                            </label>
                            <CKEditor
                                editor={ClassicEditor}
                                data={data.content_en}
                                onChange={(event, editor) =>
                                    setData("content_en", editor.getData())
                                }
                            />
                        </div>

                        <button
                            type="submit"
                            disabled={processing}
                            className="w-full bg-[#0a192f] text-yellow-500 font-black py-4 rounded-2xl uppercase tracking-widest hover:bg-navy-800 transition disabled:opacity-50"
                        >
                            {processing
                                ? "Publishing..."
                                : "Publish Intelligence Report"}
                        </button>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
