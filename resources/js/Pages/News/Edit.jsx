import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Head, useForm } from "@inertiajs/react";
import { useState } from "react";
import { CKEditor } from "@ckeditor/ckeditor5-react";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default function Edit({ news }) {
    const [lang, setLang] = useState("id");

    // Mengambil data lama dari database ke dalam form
    const { data, setData, put, processing } = useForm({
        title_id: news.title_id,
        content_id: news.content_id,
        title_en: news.title_en,
        content_en: news.content_en,
    });

    const submit = (e) => {
        e.preventDefault();
        put(route("news.update", news.id));
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
                    <form
                        onSubmit={submit}
                        className="bg-white p-8 rounded-3xl shadow-sm border border-gray-100"
                    >
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
                        {/* Title & CKEditor inputs similar to Create.jsx, prepopulated with 'data' */}
                        {/* ... (input dan CKEditor fields) ... */}
                        <button
                            type="submit"
                            disabled={processing}
                            className="bg-[#0a192f] text-yellow-500 font-bold px-10 py-4 rounded-full"
                        >
                            {processing ? "UPDATING..." : "SAVE CHANGES"}
                        </button>
                    </form>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
